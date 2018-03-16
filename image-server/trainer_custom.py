from __future__ import print_function
import keras
from keras.datasets import cifar10
from keras.preprocessing.image import ImageDataGenerator
from keras.models import Sequential
from keras.layers import Dense, Dropout, Activation, Flatten
from keras.layers import Conv2D, MaxPooling2D
import numpy as np
import os

def train(model, image, modelname):
    modelPath = "saved_models/" + modelname + ".h5"
    batch_size = 32
    epochs = 5

    labels = np.array([0])

    model.fit(image, labels,
              batch_size=batch_size,
              epochs=epochs)

    model.save(modelPath)

    return model
