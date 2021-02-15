<?php

namespace App\Services;

use App\Contracts\IMessageService;
use App\Models\Message;
use App\Repositories\MessageRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class MessageService extends ServiceProvider implements IMessageService
{
    /**
     * @var MessageRepository
     */
    protected $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    public function find(string $id): Message
    {
        try {
            $message = $this->messageRepository->find($id);

            if (empty($message->getAttributes())) {
                throw new Exception('Mensagem não encontrada.');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $message;
    }

    public function create(array $data): Message
    {
        $message = Message::factory()->make($data);

        DB::beginTransaction();

        try {
            $new_message_id = $this->messageRepository->create($message->getAttributes());
            $res = $this->find($new_message_id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return $res;
    }

    public function setSentStatus(Message $message): void
    {
        DB::beginTransaction();

        try {
            $this->messageRepository->setSentStatus($message->id, true);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
