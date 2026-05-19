# MindAssess — AI-Assisted Emotional Wellness Companion 🌿

**MindAssess** is a modern, deeply personalized mental health self-assessment and tracking platform. Built with a focus on privacy, aesthetics, and empathetic guidance, it helps users understand their mental well-being through clinically inspired screening tools, daily mood logs, and intelligent AI insights.

![MindAssess Banner](https://via.placeholder.com/1200x400.png/f6f7f5/697a59?text=MindAssess+Wellness+Companion)

> **⚠️ Disclaimer:** This platform is not a medical diagnostic tool. It is designed for educational purposes and self-reflection. If you or someone you know is in crisis, please seek professional help immediately.

---

## ✨ Key Features

### 📋 Clinically-Inspired Assessments
Access a library of validated self-assessment screening tools (e.g., PHQ-9 for Depression, GAD-7 for Anxiety, PSS for Stress). Features include:
*   Real-time client-side search and category filtering.
*   Automated scoring with dynamic severity indicators.
*   Historical tracking of assessment results.

### 🤖 AI-Powered Wellness Insights & Chatbot
*   **Insights Engine:** Automatically analyzes your recent mood logs, sleep patterns, and assessment scores to generate personalized self-care recommendations on your dashboard.
*   **MindAssess Buddy:** An integrated, floating AI chatbot that offers empathetic listening, quick coping strategies (like breathing exercises), and wellness guidance.

### 📊 Mood & Sleep Tracking
*   **Daily Check-ins:** Log your emotional state using an intuitive emoji-based interface, along with sleep hours, energy levels, and personal notes.
*   **30-Day Analytics:** Interactive line charts mapping your mood, sleep, and energy correlations over time.
*   **Calendar Heart Visualization:** A unique calendar view where each day is represented by an SVG heart. The outer outline color indicates your mood score, while the inner outline reflects your sleep quality.

### 🎨 Beautiful, Themed UI/UX
*   **Premium Glassmorphism Aesthetics:** Clean, calming UI tailored for psychological safety.
*   **5 Pastel Color Themes:** Choose your vibe — Sage Garden, Lavender Dream, Rose Garden, Ocean Breeze, or Sunset Peach.
*   **Dark Mode Support:** Built-in seamless toggling between light and dark modes to reduce eye strain.

### 🚨 Localized Crisis Support
*   A dedicated crisis center module that automatically prioritizes emergency helplines and resources based on your specified country.

### 📝 Cozy Journaling
*   A private space to write daily reflections, helping to contextualize your mood data over time.

---

## 🛠 Tech Stack

MindAssess is built using a modern, robust, and scalable technology stack:

*   **Backend Framework:** [Laravel 11.x](https://laravel.com) (PHP)
*   **Database:** [MongoDB](https://www.mongodb.com/) (Using `mongodb/laravel-mongodb` Eloquent driver)
*   **Frontend Templating:** Laravel Blade
*   **Styling:** [Tailwind CSS](https://tailwindcss.com/) with custom variable-based theming
*   **UI Components:** [DaisyUI](https://daisyui.com/)
*   **Interactivity:** [Alpine.js](https://alpinejs.dev/)
*   **Data Visualization:** [Chart.js](https://www.chartjs.org/)
*   **Markdown Parsing:** Marked.js (for the AI Chatbot)

---

## 🚀 Getting Started

### Prerequisites
*   PHP >= 8.2
*   Composer
*   Node.js & NPM
*   MongoDB Server (Local or Atlas)

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/Shraddhagupta37/Mental-Assess-Project.git
   cd Mental-Assess-Project
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install Frontend dependencies:**
   ```bash
   npm install
   ```

4. **Environment Setup:**
   Copy the example environment file and generate an application key.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure MongoDB:**
   Open the `.env` file and update your MongoDB connection string. Ensure the `DB_CONNECTION` is set to `mongodb`.
   ```env
   DB_CONNECTION=mongodb
   DB_HOST=127.0.0.1
   DB_PORT=27017
   DB_DATABASE=mindassess
   ```

6. **Compile Frontend Assets:**
   ```bash
   npm run build
   ```

7. **Run Database Migrations & Seeders:**
   *(Note: As this uses MongoDB, migrations are primarily for schema indexing, but seeders are critical to populate the assessments and crisis helplines).*
   ```bash
   php artisan db:seed
   ```

8. **Start the Development Server:**
   ```bash
   php artisan serve
   ```
   Visit `http://localhost:8000` in your browser.

---

## 📂 Project Structure Highlights

*   **`app/Models/`**: Contains MongoDB Eloquent models (`User`, `Assessment`, `MoodLog`, `JournalEntry`, `Result`, etc.).
*   **`app/Http/Controllers/`**: Core logic for dashboards, onboarding, tracking, and AI integration.
*   **`resources/views/`**: Blade templates. Key complex views include `dashboard.blade.php`, `mood/index.blade.php` (calendar & charts), and `layouts/main.blade.php` (Navigation, Theming, Chatbot).
*   **`resources/css/app.css`**: Contains the extensive CSS custom property (variable) definitions that power the 5 pastel themes and dark mode.

---

## 🤝 Contributing

Contributions make the open-source community an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📜 License

Distributed under the MIT License. See `LICENSE` for more information.

---

<p align="center">
  <i>Take a deep breath. You're doing great.</i>
</p>
