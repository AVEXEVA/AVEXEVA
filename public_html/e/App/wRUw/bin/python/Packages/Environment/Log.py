class Log(object):
#Creates Log of Encrypted Characters
   def __init__(self):
       return
   @staticmethod
   def Logging(Name, text, texte):
      if len(Name) > 0 and len(text) > 0 and len(texte) > 0:
         file = open(Name + '.eX', 'a+')
         file.write(text)
         file.close()
         file = open(Name + '.Te', 'a+')
         file.write(texte)
         file.close()
         file = open(Name + '.eXTe', 'a+')
         file.write(text + ' : ' + texte)
         file.close()
         print('Logged')
      else:
         print('Nothing to Log')
