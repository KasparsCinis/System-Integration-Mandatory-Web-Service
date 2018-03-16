from flask import Flask, url_for
app = Flask(__name__)

password = "3p4tow345owh34"

@app.route('/create', methods = ['POST'])
def api_create():
    #todo: create an empty model
    return 'Welcome'

@app.route('/train')
def api_train():
    #todo: train a model with specified images
    return 'Welcome'

@app.route('/test')
def api_test():
    #todo: test a model with specified images
    return 'List of ' + url_for('api_articles')

if __name__ == '__main__':
    app.run()