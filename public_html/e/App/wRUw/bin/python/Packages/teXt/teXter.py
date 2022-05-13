#teXter
#Class teXter
class teXter(object):
    ###Creates Objects from Imports
   @staticmethod
   def __import__(self):
      self.Lexicon = Lexicon()
   def __init__(self):
       self.__import__(self)
       return
   ###Reiterates Encrpyt Split Text
   def teXtee(self, text):
      self.tex = text.strip()
      self.text = text + ' = '
      self.te = ''
      self.t = self.tex[0]
      while len(self.tex) != 0:
         self.te = self.teXteX(self, self.tex, self.tex, 0, 0) + self.tex[0:1] + self.tex + self.teXteX(self, self.tex, self.tex, 1, 1)
         self.tex = self.tex[1:]
         self.text = self.text + self.te
      return self.text

   ###Run
   @staticmethod
   def teXteX(self, text, tex, Ex, It):
      return str(self.teXteXt(self, text, tex, Ex, It))

   ###Polarity Branch Encryption
   @staticmethod
   def teXteXt(self, text, tex, Ex, It):
      t = tex[0:1]
      x = tex[1:len(tex)]
      if Ex == 0:
         if It == 0:
            if len(t) == '' or x == '':
               return self.Lexicon.Cypher(t, It) if len(t) > 0 else str(self.teXteXt(self, text, t, Ex, It)) + self.Lexicon.Cypher(x, It) if len(t) > 0 else str(self.teXteXt(self, text, x, Ex, It)) 
            else:
               return self.Lexicon.Cypher(t, It) if len(t) > 0 else str(self.teXteXt(self, text, t, Ex, It)) + self.teXteX(self, text, x, Ex, It)
         else:
            if len(t) == '' or x == '':
               return self.Lexicon.Cypher(x, It) if len(t) > 0 else str(self.teXteXt(self, text, x, Ex, It)) + self.Lexicon.Cypher(t, It)  if len(t) > 0 else str(self.teXteXt(self, text, t, Ex, It)) 
            else:
               return self.teXteX(self, text, x, Ex, It) + self.Lexicon.Cypher(t, It) if len(t) > 0 else str(self.teXteXt(self, text, t, Ex, It)) 
      else:
         if It == 0:
            if len(t) == '' or x == '':
               return self.Lexicon.Cypher(t, It) if len(t) > 0 else str(self.teXteXt(self, text, t, Ex, It)) + self.Lexicon.Cypher(x, It) if len(t) > 0 else str(self.teXteXt(self, text, x, Ex, It)) 
            else:
               return self.teXteX(self, text, t, Ex, It) + self.Lexicon.Cypher(x, It) if len(t) > 0 else str(self.teXteXt(self, text, x, Ex, It)) 
         else:
            if len(t) == '' or x == '':
               return self.Lexicon.Cypher(x, It) if len(t) > 0 else str(self.teXteXt(self, text, x, Ex, It)) + self.Lexicon.Cypher(t, It) if len(t) > 0 else str(self.teXteXt(self, text, t, Ex, It)) 
            else:
               return self.Lexicon.Cypher(x, It) if len(t) > 0 else str(self.teXteXt(self, text, x, Ex, It)) + self.teXteX(self, text, t, Ex, It)
   
