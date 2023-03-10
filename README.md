# Face Detection System for Student Attendance Records and Laboratory Access project source code

## Abstract 
> The Covid-19 pandemic has a considerable impact on any aspects of life, especially in education. During the new normal transition, the limited face-to-face learning is implemented gradually to improve the quality of learning. It is required to automate of student attendance data considering that current attendance system and accessing the laboratory are still using contact devices/documents. It increases the risk of transmitting the Covid-19 virus. This research aims to develop face detection system for student attendance records and laboratory access. The research method uses prototyping method involving communication, quick plan, modeling quick design, construction of prototype, also deployment-delivery and feedback. The system utilizes an algorithm of deep learning by 200 student faces training data. Based on the test results, the model has an accuracy level on the data test by 90%. In this case the system has the potential to detect faces with optimal performance. The image recognition results will be connected and integrated with academic information system and laboratory door lock. This system will open the door automatically and send information data to the academic information system if the face can be identified correctly. The Implementation of face detection technology is a solution about automation in student attendance records and contactless door system. 
[See Full Extended Abstract](https://drive.google.com/file/d/1iir-QRB2c7h54ubkds0G89aTVKVf4ese/view?usp=share_link)

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
