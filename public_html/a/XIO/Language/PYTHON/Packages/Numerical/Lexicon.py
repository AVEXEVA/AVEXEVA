class VLexicon(object):
    return
class ALexicon(object):
    
   def __init__(self):
       return
   #Number
   @staticmethod
   def Number(self):
      return {
            '0' : self.Zero,
            '1' : self.One,
            '2' : self.Two,
            '4' : self.Four,
            '8' : self.Eight,
            '5' : self.Five,
            '7' : self.Seven,
            '6' : self.Six,
            '9' : self.Nine,
            '3' : self.Three,
      }
   @staticmethod
   def Zero(It):  return 0 if It == 0 else 3
   @staticmethod
   def One(It):   return 2 if It == 0 else 0
   @staticmethod
   def Two(It):   return 4 if It == 0 else 1
   @staticmethod
   def Four(It):  return 8 if It == 0 else 2
   @staticmethod
   def Eight(It): return 5 if It == 0 else 4
   @staticmethod
   def Five(It):  return 7 if It == 0 else 8
   @staticmethod
   def Seven(It): return 9 if It == 0 else 5
   @staticmethod
   def Nine(It):  return 6 if It == 0 else 7
   @staticmethod
   def Six(It):   return 3 if It == 0 else 9
   @staticmethod
   def Three(It): return 0 if It == 0 else 6
