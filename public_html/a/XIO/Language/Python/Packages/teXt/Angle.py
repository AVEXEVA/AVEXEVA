#Angle
##Class Angle
class Angle:
#Calculates {Chain Mininum, Pivot, Maximum Chain} Range Modifier (%) + Minumum
   #Importer
   @staticmethod
   def __import__(self):
      self.Lexicon = Lexicon()
      self.Dictionary = Dictionary()
      
   #Initializer
   def __init__(self):
      self.__import__(self)
      return

   #LetterSpace
   def LetterSpace(self, X, Xt, t):
      n = 0
      pre = self.SimpleSpace(self, X, 0, Xt)
      Xt = self.Dictionary.KeyIndex(self.Lexicon.Lettered(), Xt)
      post = self.SimpleSpace(self, t, 1, Xt)
      return ((post - pre) % Xt) + pre if Xt > 1 else Xt

   #Turnt Key Character Space to Pivot
   @staticmethod
   def SimpleSpace(self, Xt, It, e):
      n = 0
      num = 0
      for X in Xt:
         num = 0
         if X == e or X == ' ':
            continue
         for t in Xt:
            X = self.Lexicon.Cypher(X, It)
            if X == t:
               break
            else:
               num = num + 1
         n = n + num
      return n
   
   #Polarity Angle Encrpytion
   @staticmethod
   def SpecialSpace(e, X):
      if e != '\\' and e != '/':
         return e
      elif e == '\\' and X == 0:
         return '\\'
      else:
         return '/'
