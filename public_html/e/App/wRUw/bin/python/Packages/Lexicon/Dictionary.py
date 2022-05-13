from Lexicon import Lexicon

class Dictionary(object):
   #Reads Dictionary File and Returns Dictionary {Key(Char) : Words}
   @staticmethod
   def __import__(self):
      self.Lexicon = Lexicon()

   #Initializer
   def __init__(self):
      self.__import__(self)
      return

   #Reader of File
   def Read(self, Name):
      words = self.Lexicon.Dictionary()
      try:
         with open(Name, 'r') as f:
            words = self.FileWords(f)
            print('Dictionary Loaded')
         f.close()
      except:
         print('404 Dictionary')
      return words

   #Split File's Lines to Words
   @staticmethod
   def FileWords(file):
      words = {}
      while line := file.readline():
               words[line[:1]].append(line.strip())
      return words
   
   #Finds Index of Key in Dictionary
   @staticmethod
   def KeyIndex(diction, t):
      return [idx for idx, key in enumerate(diction) if key[0]== t].pop()
