<?php

namespace hesabro\notif;

use Exception;
use hesabro\notif\interfaces\Notify;
use Yii;
use yii\base\Module as BaseModule;

/**
 * @property-read int|null $clientId
 * @property-read array $eventsKey
 * @property-read array $eventsValue
 * @property-read array $eventsAll
 */
class Module extends BaseModule
{
    public bool $enable = true;

    public ?string $websiteName = null;

    public ?string $user = null;

    public ?string $sms = null;

    public ?string $email = null;

    public ?string $ticket = null;

    public ?string $clientComponent = null;

    public array $events = [];

    public function init()
    {
        parent::init();

        if ($this->sms && (!is_string($this->sms) || !((new $this->sms()) instanceof Notify))) {
            throw new Exception('sms attribute in Notif module listener must be instance of Notify', 500);
        }

        if ($this->email && (!is_string($this->email) || !((new $this->email()) instanceof Notify))) {
            throw new Exception('email attribute in Notif module, must be instance of Notify', 500);
        }

        if ($this->ticket && (!is_string($this->ticket) || !((new $this->ticket()) instanceof Notify))) {
            throw new Exception('ticket attribute in Notif module, must be instance of Notify', 500);
        }
    }

    public function getClientId(): int
    {
        if ($this->clientComponent) {
            return Yii::$app->{$this->clientComponent}->id;
        }

        return 1;
    }

    public function getEventsKey(): array
    {
        return array_reduce($this->events, fn ($carry, $item) => array_merge($carry, array_keys(($item['items'] ?? []))), []);
    }

    public function getEventsValue(): array
    {
        return array_reduce($this->events, fn ($carry, $item) => array_merge($carry, array_values(($item['items'] ?? []))), []);
    }

    public function getEventsAll(): array
    {
        return array_reduce($this->events, fn ($carry, $item) => array_merge($carry, ($item['items'] ?? [])), []);
    }

    public function getEventsByGroup(string $groupName): array
    {
        $group = current(array_filter($this->events, fn($item) => ($item['group'] ?? null) === $groupName));

        return $group['items'] ?? [];
    }

    public static function t($category, $message, $params = [], $language = null): string
    {
        return Yii::t('hesabro/notif/' . $category, $message, $params, $language);
    }
}
