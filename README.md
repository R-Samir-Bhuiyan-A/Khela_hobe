# 🔥 Free Fire Tournament App (Modular Design)

This is a fully modular app to manage Free Fire tournaments with **real money entry**, **prize distribution**, **wallet system**, and **admin panel**, built using **Flutter (frontend)** and **PHP with JSON (backend)**.

Designed so each feature is **modular** — no feature breaks the others, and new features can be added easily.

---

## 📁 Folder Structure (Modular)

```plaintext
project-root/
│
├── backend/                  # Backend PHP + JSON
│   ├── json/                 # JSON file DBs
│   │   ├── users.json
│   │   ├── tournaments.json
│   │   ├── matches.json
│   │   ├── transactions.json
│   │   └── wallet.json
│   │
│   ├── core/                 # Core reusable modules
│   │   ├── auth.php
│   │   ├── file_helper.php
│   │   ├── response.php
│   │   └── validate.php
│   │
│   ├── modules/              # Features (Modular APIs)
│   │   ├── auth/
│   │   │   ├── login.php
│   │   │   └── register.php
│   │   │
│   │   ├── tournament/
│   │   │   ├── get.php
│   │   │   ├── join.php
│   │   │   └── create.php
│   │   │
│   │   ├── wallet/
│   │   │   ├── deposit.php
│   │   │   ├── withdraw.php
│   │   │   └── get_balance.php
│   │   │
│   │   └── admin/
│   │       ├── approve_withdrawal.php
│   │       └── post_result.php
│   │
│   └── index.php             # (Optional) Route handler
│
├── flutter_app/              # Flutter UI (Modular Screens)
│   ├── lib/
│   │   ├── screens/
│   │   ├── services/
│   │   ├── models/
│   │   ├── utils/
│   │   └── main.dart
│
└── README.md
```

---

## 🧠 Modular Design Concepts

- Each API lives in a **self-contained module**.
- Each screen in Flutter uses **service classes** for network logic.
- JSON files are modified through **helper functions**, never directly.

---

## 📦 JSON File Format Samples

### users.json

```json
[
  {
    "id": "u1",
    "email": "test@gmail.com",
    "password": "hashed",
    "ff_uid": "123456789",
    "wallet": 40
  }
]
```

### tournaments.json

```json
[
  {
    "id": "t1",
    "title": "Solo Battle",
    "entry_fee": 10,
    "slots": 100,
    "prize_pool": 100,
    "joined": ["u1", "u2"],
    "match_time": "2025-06-01 20:00",
    "room_id": "abcd",
    "room_password": "xyz"
  }
]
```

---

## ⚙️ Modular Backend API (PHP)

### 🧩 Example: `join.php`

```php
<?php
require_once '../../core/file_helper.php';
require_once '../../core/response.php';
require_once '../../core/validate.php';

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['user_id'];
$tournamentId = $data['tournament_id'];

$tournaments = read_json('../../json/tournaments.json');
$users = read_json('../../json/users.json');

// validation
$tournament = find_by_id($tournaments, $tournamentId);
$user = find_by_id($users, $userId);

if (!$tournament || !$user) return error("Invalid user or tournament.");

if (in_array($userId, $tournament['joined']))
    return error("Already joined.");

if ($user['wallet'] < $tournament['entry_fee'])
    return error("Not enough balance.");

// deduct money & join
$user['wallet'] -= $tournament['entry_fee'];
$tournament['joined'][] = $userId;

update_json_item('../../json/users.json', $userId, $user);
update_json_item('../../json/tournaments.json', $tournamentId, $tournament);

return success("Joined successfully.");
?>
```

---

## ✅ Developer Checklist

### Phase 1: MVP
- [x] Register/Login (PHP JSON-based)
- [x] Wallet system (add/deduct)
- [x] Tournament List & Join
- [x] Match Room ID view (after join)
- [x] Admin create tournaments
- [x] Result upload (admin only)

### Phase 2: Features
- [ ] Withdraw system (manual)
- [ ] Notifications (Flutter + FCM)
- [ ] Transaction history
- [ ] Match result viewer
- [ ] Flutter dark theme

### Phase 3: Advanced
- [ ] Payment API (SSLCommerz)
- [ ] Referral system
- [ ] Live UID validation via FF API (if possible)
- [ ] Leaderboard

---

## ➕ Adding New Features

Add a new feature? Just:

### 1. Add JSON Field (if needed)
E.g. `referral_code` to users.json.

### 2. Create new module
Inside `backend/modules/[feature]/your_feature.php`

### 3. Create Flutter screen + service
In `lib/screens/your_feature_screen.dart`  
In `lib/services/your_feature_service.dart`

### 4. Reuse `core/` PHP helpers

---

## 🔒 Security Tips
- Sanitize all inputs
- Use JWT/session tokens for login
- Use HTTPS
- Never trust frontend data blindly

---

## 📜 Legal Disclaimer

> This app is NOT affiliated with Garena. Running real-money tournaments may require legal registration depending on your country. Ensure users are 18+ and terms are clear.

---

## 🧑‍💻 Author

**Samir Bhuiyan**  
Email: shamirbhuiyan2@gmail.com  
GitHub: [github.com/samirdev](https://github.com/samirdev)

---
