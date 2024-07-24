# Emoji Sentiment Analyzer

## Description

The Emoji Sentiment Analyzer is a Laravel-based web application that performs sentiment analysis on text 
by extracting emojis and calculating sentiment scores based on predefined values. The application allows users to submit text containing emojis, analyze the sentiment, and view the results. It supports asynchronous processing using Laravel Queues and provides an API endpoint secured with token-based authentication.


## Installation

Follow these steps to set up the development environment:

1. Clone the repository:
    ```bash
    git clone https://github.com/avighoshjit567/emoji-sentiment-analyzer.git
    ```

2. Navigate into the project directory:
    ```bash
    cd emoji-sentiment-analyzer
    ```

3. Install the necessary dependencies:
    ```bash
    composer install
    npm install
    ```

4. Set up your environment file and configure your database settings:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. Run the migrations:
The database files are included in the "db" folder within the project directory
    ```bash
    php artisan migrate
    ```

6. Install Passport for API authentication:
    ```bash
    php artisan passport:install
    ```

7. Configure Passport in your `AuthServiceProvider`:
    ```php
    use Laravel\Passport\Passport;

    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
    }
    ```

8. Build the assets:
    ```bash
    npm run dev
    ```

## Usage

To run the application:

1. Start the development server:
    ```bash
    php artisan serve
    ```

2. Open your browser and navigate to `http://localhost:8000` to access the application.

### API Usage

1. Obtain an access token by sending a POST request to `/oauth/token` with your client credentials.

2. Use the access token to authenticate API requests by including it in the `Authorization` header:

    ```bash
    curl -H "Authorization: Bearer your-access-token" \
        http://localhost:8000/api/analyze \
        -d '{"text": "I am happy 😊"}'
    ```

### Example

Submit text containing emojis through the web interface or API to receive a sentiment score based on the emojis present in the text.

## Features

- Extracts emojis from input text.
- Calculates sentiment score based on predefined emoji values (e.g., 😊 = +1, 😢 = -1).
- Stores and displays analysis results.
- Asynchronous processing using Laravel Queues.
- API endpoint secured with token-based authentication.
- User-friendly UI with Tailwind CSS.

## Contributing

If you would like to contribute to this project, please follow these steps:

1. Fork the repository.
2. Create a new branch:
    ```bash
    git checkout -b feature/your-feature
    ```
3. Make your changes and commit them:
    ```bash
    git commit -am 'Add your feature'
    ```
4. Push to the branch:
    ```bash
    git push origin feature/your-feature
    ```
5. Create a Pull Request.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Contact

- **Avijit Ghosh** - [avighoshjit567@gmail.com](mailto:avighoshjit567@gmail.com)
- **Project Repository** - [https://github.com/avighoshjit567/emoji-sentiment-analyzer](https://github.com/avighoshjit567/emoji-sentiment-analyzer)
