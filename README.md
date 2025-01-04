# Multi-bot skeleton made with Laravel + Nutgram

- [Laravel documentation](https://laravel.com/docs/11.x)
- [Nutgram documentation](https://nutgram.dev/docs/introduction)

### Features:
- Nutgram bridge for Laravel (more flexible than provided by Nutgram)
- Multi-bot webhook handler
- Plug&play long polling command (works like webhook)

### Plans for the future:
- Callback/ReplyKeyboard helpers
- Routing helpers
- Telegram user_id store
- Send message for all users

You can freely suggest any feature in the issues.

---

### Usage
Running local long-polling which works like webhook:

`php artisan run:polling`

Set webhook for production

`php artisan set:webhook`
