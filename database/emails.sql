CREATE TABLE emails (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    subject TEXT,
    status TINYINT UNSIGNED,
    text_body LONGTEXT,
    html_body LONGTEXT,
    meta JSON,
    created_at DATETIME,
    sent_at DATETIME
);