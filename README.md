# Face Detection System for Student Attendance Records and Laboratory Access project source code

Simple REST CRUD API part of project titled Face Detection System for Student Attendance Records and Laboratory Access
See python files for the IoT in folder /python *the source code is being shared for educational purpose only*

## Installation

clone this project

```bash
https://github.com/fahimanabila/face_detection_iiiex.git
```
copy .env.example to .env

```bash
cp .env.example .env
```

install composer

```bash
composer install
```

generate key

```bash
php artisan key:generate
```

migrate database

```bash
php artisan migrate
```

## Usage

run server

```bash
php artisan serve
```

## API Endpoint

### POST /absen
  Mengirim data NIM dan status absensi ketika wajah mahasiswa berhasil dideteksi.

* **Method**

  ```http
  POST /absen
  ```

* **Data Params**

  | Parameter | Type | Description |
  | :--- | :--- | :--- |
  | `nim` | `string` | **Required**. NIM Mahasiswa |
  | `status` | `string` | **Required**. Status Absensi |

* **Response**
  * **Code:** `201` or `409`
  * **Content:**
    ```javascript
    {
      'success' => bool,
      'message' => string
    }
    ```

### GET /usernim
  Mengambil seluruh data nama dan nim dari user.

* **Method**

  ```http
  GET /usernim
  ```

* **Data Params**

  None

* **Response**
  * **Code:** `200`
  * **Content:**
    ```javascript
    {
      'success' => bool,
      'message' => string,
      'data'    => string
    }
    ```