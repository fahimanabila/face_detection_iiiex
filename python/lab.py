import face_recognition
import cv2
import numpy as np
import os
import pyautogui
import re
import requests

video_capture = cv2.VideoCapture(0)

BASE_PATH = os.path.dirname(os.path.abspath(__file__))
IMG_PATH = os.path.join(BASE_PATH, 'img')
SERVER_API = "http://localhost:8000/"

known_face_encodings = []
known_face_names = []

face_locations = []
face_encodings = []
face_names = []
process_this_frame = True
scale_frame = 120
user_nim = {}

def load_image():
    global known_face_encodings, known_face_names, user_nim, IMG_PATH, SERVER_API

    res = requests.get(SERVER_API + "api/usernim")
    if res.status_code == 200:
        user_nim = res.json()['data']

    known_face_encodings = []
    known_face_names = []
    
    for root, dirs, files in os.walk(IMG_PATH):
        for file in files:
            if file.endswith('.png') or file.endswith('.jpg'):
                path = os.path.join(root, file)
                label = os.path.basename(path).split('.')[0].replace(' ', '-').lower()

                if label in user_nim:
                    name = user_nim[label]

                    image = face_recognition.load_image_file(path)
                    face_encoding = face_recognition.face_encodings(image)[0]

                    known_face_encodings.append(face_encoding)
                    known_face_names.append(name)

load_image()

while True:
    ret, frame = video_capture.read()
    frame_ori = frame.copy()

    if process_this_frame:
        small_frame = cv2.resize(frame, (0, 0), fx=0.25, fy=0.25)
        rgb_small_frame = small_frame[:, :, ::-1]
        
        face_locations = face_recognition.face_locations(rgb_small_frame)
        face_encodings = face_recognition.face_encodings(rgb_small_frame, face_locations)

        face_names = []
        for face_encoding in face_encodings:
            matches = face_recognition.compare_faces(known_face_encodings, face_encoding, tolerance=0.5)
            name = "Unknown"
            nim = ""

            face_distances = face_recognition.face_distance(known_face_encodings, face_encoding)
            best_match_index = np.argmin(face_distances)

            if matches[best_match_index]:
                name = known_face_names[best_match_index]
                nim = list(user_nim.keys())[list(user_nim.values()).index(name)]
                name += " {:.2f}%".format(1 - face_distances[best_match_index])

            face_names.append(name)

    process_this_frame = not process_this_frame

    for (top, right, bottom, left), name in zip(face_locations, face_names):
        top *= 4
        right *= 4
        bottom *= 4
        left *= 4

        cv2.rectangle(frame, (left, top), (right, bottom), (0, 0, 255), 2)

        cv2.rectangle(frame, (left, bottom - 35), (right, bottom), (0, 0, 255), cv2.FILLED)
        font = cv2.FONT_HERSHEY_DUPLEX
        cv2.putText(frame, name, (left + 6, bottom - 6), font, 1.0, (255, 255, 255), 1)

    w = int(frame.shape[1] * scale_frame / 100)
    h = int(frame.shape[0] * scale_frame / 100)
    dim = (w, h)

    resized = cv2.resize(frame, dim, interpolation = cv2.INTER_AREA)

    cv2.imshow('Face Detection', resized)

    key = cv2.waitKey(1) & 0xFF

    if key == ord('q'):
        break

    if key == ord('r'):
        load_image()

    if key == ord('a'):
        if (name != "unknown" and nim != ""):
            res = requests.post(SERVER_API + "api/absensi", data= {"nim" : nim, "status" : "masuk"})
            if res.status_code == 201:
                pyautogui.alert("Absensi berhasil", "Face Detection")
                

    if key == ord('s'):
        label = pyautogui.prompt("Masukan nama anda : ", "Face Detection")
        if label is not None:
            # check label in known_face_names
            if label.lower() in known_face_names:
                confirm = pyautogui.confirm("Nama sudah ada mau ganti?", "Face Detection", buttons=['Ya', 'Tidak'])
                if confirm == 'Ya':
                    for f in os.listdir(IMG_PATH):
                        if re.search(label + ".*", f) :
                            os.remove(os.path.join(IMG_PATH, f))

                    cv2.imwrite(os.path.join(IMG_PATH, label + ".jpg"), frame_ori)
            else:
                cv2.imwrite(os.path.join(IMG_PATH, label + ".jpg"), frame_ori)
            
            load_image()
            pyautogui.alert("Berhasil menyimpan gambar", "Face Detection")

# Release handle to the webcam
video_capture.release()
cv2.destroyAllWindows()
