
CREATE TABLE IF NOT EXISTS users
(

    id         BIGSERIAL PRIMARY KEY,
    email      VARCHAR(255) UNIQUE NOT NULL,
    username   VARCHAR(255) UNIQUE NOT NULL,
    first_name VARCHAR(100),
    last_name  VARCHAR(100),
    phone      VARCHAR(20),
    is_active  BOOLEAN   DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS user_credentials
(
    id         BIGSERIAL PRIMARY KEY,
    user_id    BIGINT       NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    password   VARCHAR(255) NOT NULL,
    created_by BIGINT       NOT NULL REFERENCES users (id),
    updated_by BIGINT       NOT NULL REFERENCES users (id),
    deleted_by BIGINT       NOT NULL REFERENCES users (id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS roles
(
    id          BIGSERIAL PRIMARY KEY,
    name        VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    is_default  BOOLEAN   DEFAULT FALSE,
    created_by  BIGINT             NOT NULL REFERENCES users (id),
    updated_by  BIGINT             NOT NULL REFERENCES users (id),
    deleted_by  BIGINT             NOT NULL REFERENCES users (id),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at  TIMESTAMP
);

CREATE TABLE IF NOT EXISTS permissions
(
    id          BIGSERIAL PRIMARY KEY,
    name        VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_by  BIGINT              NOT NULL REFERENCES users (id),
    updated_by  BIGINT              NOT NULL REFERENCES users (id),
    deleted_by  BIGINT              NOT NULL REFERENCES users (id),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at  TIMESTAMP
);

CREATE TABLE IF NOT EXISTS role_permissions
(
    id            BIGSERIAL PRIMARY KEY,
    role_id       BIGINT NOT NULL REFERENCES roles (id) ON DELETE CASCADE,
    permission_id BIGINT NOT NULL REFERENCES permissions (id) ON DELETE CASCADE,
    created_by    BIGINT NOT NULL REFERENCES users (id),
    updated_by    BIGINT NOT NULL REFERENCES users (id),
    deleted_by    BIGINT NOT NULL REFERENCES users (id),
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at    TIMESTAMP
);

CREATE TABLE IF NOT EXISTS user_roles
(
    id         BIGSERIAL PRIMARY KEY,
    user_id    BIGINT NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    role_id    BIGINT NOT NULL REFERENCES roles (id) ON DELETE CASCADE,
    created_by BIGINT NOT NULL REFERENCES users (id),
    updated_by BIGINT NOT NULL REFERENCES users (id),
    deleted_by BIGINT NOT NULL REFERENCES users (id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS courses
(
    id           BIGSERIAL PRIMARY KEY,
    title        VARCHAR(255) NOT NULL,
    description  TEXT,
    is_published BOOLEAN   DEFAULT FALSE,
    published_at TIMESTAMP,
    owner_id     BIGINT       NOT NULL REFERENCES users (id),
    created_by   BIGINT       NOT NULL REFERENCES users (id),
    updated_by   BIGINT       NOT NULL REFERENCES users (id),
    deleted_by   BIGINT       NOT NULL REFERENCES users (id),
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at   TIMESTAMP
);

CREATE TABLE IF NOT EXISTS user_courses
(
    id         BIGSERIAL PRIMARY KEY,
    user_id    BIGINT NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    course_id  BIGINT NOT NULL REFERENCES courses (id) ON DELETE CASCADE,
    created_by BIGINT NOT NULL REFERENCES users (id),
    updated_by BIGINT NOT NULL REFERENCES users (id),
    deleted_by BIGINT NOT NULL REFERENCES users (id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS lessons
(
    id           BIGSERIAL PRIMARY KEY,
    title        VARCHAR(255) NOT NULL,
    content      TEXT,
    is_published BOOLEAN   DEFAULT FALSE,
    published_at TIMESTAMP,
    created_by   BIGINT       NOT NULL REFERENCES users (id),
    updated_by   BIGINT       NOT NULL REFERENCES users (id),
    deleted_by   BIGINT       NOT NULL REFERENCES users (id),
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at   TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users_progress
(
    id           BIGSERIAL PRIMARY KEY,
    user_id      BIGINT NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    lesson_id    BIGINT NOT NULL REFERENCES lessons (id) ON DELETE CASCADE,
    course_id    BIGINT NOT NULL REFERENCES courses (id) ON DELETE CASCADE,
    progress     INT       DEFAULT 0 check (progress >= 0 AND progress <= 100),
    is_completed BOOLEAN   DEFAULT FALSE,
    completed_at TIMESTAMP,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at   TIMESTAMP
);
