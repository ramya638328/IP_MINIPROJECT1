
CREATE DATABASE IF NOT EXISTS online_quiz;
USE online_quiz;

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'student'
);

CREATE TABLE IF NOT EXISTS quizzes (
    quiz_id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_name VARCHAR(100) NOT NULL,
    category VARCHAR(100) NOT NULL,
    duration INT NOT NULL DEFAULT 10,
    total_questions INT NOT NULL DEFAULT 5
);

CREATE TABLE IF NOT EXISTS questions (
    question_id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_answer CHAR(1) NOT NULL,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(quiz_id)
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS results (
    result_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    quiz_id INT NOT NULL,
    score INT NOT NULL DEFAULT 0,
    correct_answers INT NOT NULL DEFAULT 0,
    incorrect_answers INT NOT NULL DEFAULT 0,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (quiz_id) REFERENCES quizzes(quiz_id)
);

INSERT INTO quizzes
(quiz_name, category, duration, total_questions)
SELECT 'General Knowledge Quiz', 'General Knowledge', 10, 5
WHERE NOT EXISTS (
    SELECT 1 FROM quizzes
    WHERE quiz_name = 'General Knowledge Quiz'
);

SET @quiz_id = (
    SELECT quiz_id FROM quizzes
    WHERE quiz_name = 'General Knowledge Quiz'
    LIMIT 1
);

INSERT INTO questions
(quiz_id, question, option_a, option_b, option_c, option_d, correct_answer)
SELECT @quiz_id, 'What is the capital of India?',
'Mumbai', 'New Delhi', 'Chennai', 'Kolkata', 'B'
WHERE NOT EXISTS (
    SELECT 1 FROM questions
    WHERE quiz_id = @quiz_id
    AND question = 'What is the capital of India?'
);

INSERT INTO questions
(quiz_id, question, option_a, option_b, option_c, option_d, correct_answer)
SELECT @quiz_id, 'Which language is used to style web pages?',
'HTML', 'Python', 'CSS', 'Java', 'C'
WHERE NOT EXISTS (
    SELECT 1 FROM questions
    WHERE quiz_id = @quiz_id
    AND question = 'Which language is used to style web pages?'
);

INSERT INTO questions
(quiz_id, question, option_a, option_b, option_c, option_d, correct_answer)
SELECT @quiz_id, 'Which planet is known as the Red Planet?',
'Earth', 'Mars', 'Jupiter', 'Venus', 'B'
WHERE NOT EXISTS (
    SELECT 1 FROM questions
    WHERE quiz_id = @quiz_id
    AND question = 'Which planet is known as the Red Planet?'
);

INSERT INTO questions
(quiz_id, question, option_a, option_b, option_c, option_d, correct_answer)
SELECT @quiz_id, 'How many days are there in a week?',
'5', '6', '7', '8', 'C'
WHERE NOT EXISTS (
    SELECT 1 FROM questions
    WHERE quiz_id = @quiz_id
    AND question = 'How many days are there in a week?'
);

INSERT INTO questions
(quiz_id, question, option_a, option_b, option_c, option_d, correct_answer)
SELECT @quiz_id, 'Which device is used to type text?',
'Monitor', 'Keyboard', 'Speaker', 'Printer', 'B'
WHERE NOT EXISTS (
    SELECT 1 FROM questions
    WHERE quiz_id = @quiz_id
    AND question = 'Which device is used to type text?'
);

SELECT * FROM quizzes;
SELECT * FROM questions;
