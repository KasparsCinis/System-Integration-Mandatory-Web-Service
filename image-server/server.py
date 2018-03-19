import os
import os.path
import requests
import numpy as np
import json
from io import BytesIO
from PIL import Image
import keras
from keras.datasets import cifar10
from keras.preprocessing.image import ImageDataGenerator
from keras.models import Sequential
from keras.layers import Dense, Dropout, Activation, Flatten
from keras.layers import Conv2D, MaxPooling2D
from keras.preprocessing import image
from keras.models import load_model
from flask import Flask, url_for, request, abort, jsonify
from create_empty import create_empty
from trainer_custom import train
app = Flask(__name__)

password = "3p4tow345owh34"
models = {}



@app.route('/create', methods = ['POST'])
def api_create():
    if (request.json['password'] != password):
        abort(400)
    if 'model' not in request.json:
        abort(400)

    create_empty(request.json['model'])
    load_model_memory(request.json['model'])

    return 'Model Created'

@app.route('/train', methods = ['POST'])
def api_train():
    if (request.json['password'] != password):
        abort(400)
    if 'model' not in request.json:
        abort(400)
    if 'image' not in request.json:
        abort(400)

    request.json['model'] = str(request.json['model'])

    response = requests.get(request.json['image'])
    img = Image.open(BytesIO(response.content))
    img = img.resize((32, 32), Image.ANTIALIAS)
    img_tensor = image.img_to_array(img)
    img_tensor = img_tensor.astype('float32')
    img_tensor /= 255

    model = load_model_memory(request.json['model'])
    model = train(model, img_tensor, request.json['model'])
    models[request.json['model']] = model

    return 'Model Trained'

@app.route('/test', methods = ['POST'])
def api_test():
    if (request.json['password'] != password):
        abort(400)
    if 'model' not in request.json:
        abort(400)
    if 'image' not in request.json:
        abort(400)

    request.json['model'] = str(request.json['model'])

    response = requests.get(request.json['image'])
    img = Image.open(BytesIO(response.content))
    img = img.resize((32, 32), Image.ANTIALIAS)
    img_tensor = image.img_to_array(img)
    img_tensor = np.expand_dims(img_tensor,axis=0)
    img_tensor /= 255.

    model = load_model_memory(request.json['model'])
    pred = model.predict(img_tensor)
    result = np_to_result(pred[0], request.json['model'])

    return jsonify(result)

def load_model_memory(modelname):
    modelPath = "saved_models/" + modelname + ".h5"

    # create a new empty model
    if not (os.path.isfile(modelPath)):
        model = create_empty(modelname)
        models[modelname] = model

    if modelname not in models:
        model = load_model(modelPath)
        models[modelname] = model
    else:
        model = models[modelname]

    return model

def np_to_result(result, modelname):

    jsonArray = {}

    if (modelname == "1"):
        jsonArray = {
            "airplane": str(round(result[0], 3)),
            "bird": str(round(result[2], 3))
        }
    else:
        jsonArray = {
            "value": str(round(result[0], 3))
        }

    return jsonArray

if __name__ == '__main__':
    app.run(host= '0.0.0.0')
    #load_model("saved_models/1.h5")