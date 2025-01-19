# Airplane Ticket Reservation API

## Setup Instructions
1. Clone the repository.
   ```bash
   git clone https://github.com/ishaqj/airplane-api-reservation.git
   cd airplane-ticket-reservation

2. Install dependencies.
   ```bash
   composer install
   
3. Copy the `.env.example` file to `.env`
   ```bash
    cp .env.example .env
4. Generate the application key.
   ```bash
   php artisan key:generate
5. Run migrations.
   ```bash
   php artisan migrate
6. Start the development server.
   ```bash
    php artisan serve
   
7. Use Postman or similar tools to interact with the API.
   Please use `Accept` and `Content-Type` headers as `application/json`.
    - `Content-Type: application/json`
    - `Accept: application/json`
   
    ```bash
   Create Ticket: POST http://127.0.0.1:8000/api/tickets
    Body example:
    {
        "flight_number": "SKY-123",
        "departure_time": "2025-03-10 10:00:00",
        "source": "Arlanda",
        "destination_airport": "London Heathrow",
        "passport_id": "ABC123456"
    }
   Cancel Ticket: PATCH /api/tickets/{id}/cancel
   Change Seat: PATCH /api/tickets/{id}/seat

8. You can also run the feature tests, Larastan and pint.
   ```bash
   php artisan test
   ./vendor/bin/phpstan analyse --memory-limit=2G
   ./vendor/bin/pint
   ```
 This project also uses GitHub Actions for continuous integration.  
