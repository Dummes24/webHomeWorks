CREATE TABLE goods
(
    id          SERIAL PRIMARY KEY,
    name        VARCHAR(255),
    description TEXT,
    price       NUMERIC(10, 2) NOT NULL DEFAULT 0
)
    CHARACTER SET utf8
;
