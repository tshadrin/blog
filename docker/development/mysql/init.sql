USE blog;
CREATE TABLE `rememberme_token` (
    `series`   char(88)     UNIQUE PRIMARY KEY NOT NULL,
    `value`    varchar(88)  NOT NULL,
    `lastUsed` datetime     NOT NULL,
    `class`    varchar(100) NOT NULL,
    `username` varchar(200) NOT NULL
);
