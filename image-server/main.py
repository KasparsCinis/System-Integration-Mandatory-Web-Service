from trainer_cifar10 import train_cifar
import os
import os.path
import keras
from keras.datasets import cifar10
from keras.preprocessing.image import ImageDataGenerator
from keras.preprocessing import image
from keras.models import Sequential
from keras.models import load_model
from PIL import Image
import numpy as np

modelName = "saved_models/keras_cifar10_trained_model.h5"

def load_image(img_path, show=False):

    img = image.load_img(img_path, target_size=None)
    img = img.resize((32, 32), Image.ANTIALIAS)
    img_tensor = image.img_to_array(img)                    # (height, width, channels)
    img_tensor = np.expand_dims(img_tensor, axis=0)         # (1, height, width, channels), add a dimension because the model expects this shape: (batch_size, height, width, channels)
    img_tensor /= 255.                                      # imshow expects values in the range [0, 1]

    return img_tensor


#train main cifar model if not trained yet
if not(os.path.isfile(modelName)):
    print("Starting training of main model:")
    train_cifar()

#main cifar model has to be trained for script to work
if not(os.path.isfile(modelName)):
    print("Error training cifar model. Exiting...")
    exit()

#load main cifar model
model = load_model(modelName)

new_image = load_image("test.jpg")

# check prediction
pred = model.predict(new_image)
print("PREDICTION")
for prediction in pred[0]:
    print(prediction)