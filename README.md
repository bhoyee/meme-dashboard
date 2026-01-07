## 📘 Meme Coin Alpha Dashboard

A real-time, Telegram-powered meme coin intelligence dashboard built with Laravel + Vue.js. It tracks hype signals, whale wallet activity, and contract risks — giving early insights into high-potential meme coins before they trend.

---

### 🚀 Project Goals

- Detect meme coin launches from Telegram alpha groups
- Track whale wallet buys via Solscan links
- Score hype signals and contract safety
- Trigger alerts via Telegram, email, or SMS
- Let users tag tokens and build watchlists

---

### 🧰 Tech Stack

| Layer        | Technology         | Purpose |
|-------------|--------------------|---------|
| Backend      | Laravel 11         | API, scheduler, command jobs |
| Frontend     | Vue.js 3 + Vue Router | Interactive dashboard UI |
| Database     | MySQL              | Token, signal, wallet, risk storage |
| Alerts       | Laravel Notifications | Telegram, email, SMS |
| Scraping     | Goutte / DomCrawler | Solscan + TokenSniffer HTML parsing |
| Git Workflow | Feature branching  | Modular milestone development |

---




---

### ⚙️ Setup Instructions

#### 1. Clone the Repo
```bash
git clone https://github.com/yourusername/meme-dashboard.git
cd meme-dashboard
```

#### 2. Install Laravel Dependencies
```bash
composer install
cp .env.example .env
php artisan key:generate
```

#### 3. Install Node + Vue Dependencies
```bash
npm install
npm run dev
```

#### 4. Set Up Database
```bash
php artisan migrate
```

#### 5. Run Scheduler
```bash
php artisan schedule:run
```

---

### 📡 Alert Channels

- Telegram bot (via Laravel Telegram SDK)
- Email (via Mailtrap or SMTP)
- SMS (via Vonage or Twilio)

---

### 🧠 Intelligence Sources

- Telegram groups (e.g. Meme Launchers, Whale Watchers)
- Solscan transaction links
- TokenSniffer contract audit pages

---

### 🤝 Contributing

Each milestone is developed in its own branch. To contribute:

```bash
git checkout -b mX-feature-name
```

Submit pull requests to `master` only after testing.

---

Let me know when you’re ready and I’ll help you add this to your repo and commit it to `m1-api-scheduler`. Then we’ll move on to M2 and scaffold the Vue frontend.
