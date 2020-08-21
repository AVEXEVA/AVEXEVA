#Expression
##Class Expression
class Expression(object):
   
   ###Creates Objects from Imports
   @staticmethod
   def __import__(self):
      self.Lexicon = Lexicon()
      self.Angle = Angle()
      
   ###Initializer
   def __init__(self):
      self.__import__(self)
      return
   
   ###Processes Encrypted Text to Words
   def Expressive(self, text, words):
      expression = []
      n = 0
      for char in text:
         expression.append(words[self.Lexicon.LettersIndex()[self.Angle.LetterSpace(text[:n], char, text[n:])]])
         n = n + 1
         return ' '.join(expression)
