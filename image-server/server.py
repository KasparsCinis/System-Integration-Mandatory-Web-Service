import os
import os.path
import numpy as np
import keras
from keras.datasets import cifar10
from keras.preprocessing.image import ImageDataGenerator
from keras.models import Sequential
from keras.layers import Dense, Dropout, Activation, Flatten
from keras.layers import Conv2D, MaxPooling2D
from keras.models import load_model
from flask import Flask, url_for, request, abort
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

    model = load_model_memory(request.json['model'])
    model = train(model, request.json['image'], request.json['model'])
    models[request.json['model']] = model

    return 'Model Trained'

@app.route('/test', methods = ['POST'])
def api_test():
    model = load_model_memory(request.json['model'])
    pred = model.predict(request.json['image'])

    return 'Result: ' + pred

def load_model_memory(modelname):
    modelPath = "saved_models/" + modelname + ".h5"
    print(modelPath)
    # create a new empty model
    if not (os.path.isfile(modelPath)):
        print("model doesn't exist")
        abort(400)

    if modelname not in models:
        model = load_model(modelPath)
        models[modelname] = model
    else:
        model = models[modelname]

    return model



if __name__ == '__main__':
    app.run(host= '0.0.0.0')
    #load_model("saved_models/cifar10.h5")