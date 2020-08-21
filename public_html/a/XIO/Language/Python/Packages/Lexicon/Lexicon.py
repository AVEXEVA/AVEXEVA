class Lexicon (object):
   
   #Importer
   @staticmethod
   def __import__(self):
      return
   #Initializer
   def __init__(self):
      self.__import__(self)
      return

   #Cypher
   def Cypher(self, e, It):
      return self.turnt(self, e, It)

   #turnt
   @staticmethod
   def turnt(self, e, It):
      if len(e) == 0:
         return str(self.l0(It))
      elif len(e) == 1 and e.isalpha():
         return str(self.Letter(self)[e](It))
      elif len(e) == 1 and e.isdigit():
         return str(self.Number(self)[e](It))
      else:
         return ''

   #Dictionary
   @staticmethod
   def Dictionary():
      words = {}
      for Char in 'AVXTIHUCGJOEBLRPQDSYMWNZKF':
         words[Char] = []
      return words

   #LetterIndex
   @staticmethod
   def LettersIndex():
      letters = {}
      number = 0
      for letter in 'AVXTIHUCGJOEBLRPQDSYMWNZKF':
         letters[number] = [letter]
      return letters

   #Lettered
   def Lettered(self):
      return self.Letter(self)
   
   #Letter
   @staticmethod
   def Letter(self):
      return {
            'A' : self.A,
            'V' : self.V,
            'X' : self.X,
            'T' : self.T,
            'I' : self.I,
            'H' : self.H,
            'U' : self.U,
            'C' : self.C,
            'G' : self.G,
            'J' : self.J,
            'O' : self.O,
            'E' : self.E,
            'B' : self.B,
            'L' : self.L,
            'R' : self.R,
            'P' : self.P,
            'Q' : self.Q,
            'D' : self.D,
            'S' : self.S,
            'Y' : self.Y,
            'W' : self.W,
            'M' : self.M,
            'N' : self.N,
            'Z' : self.Z,
            'K' : self.K,
            'F' : self.F,
         }

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

   #Letter turnt
   @staticmethod
   def l0(It):return ''
   @staticmethod
   def A(It):return 'V' if It == 0 else 'F'
   @staticmethod
   def V(It):return 'X' if It == 0 else 'A'
   @staticmethod
   def X(It):return 'T' if It == 0 else 'V'
   @staticmethod
   def T(It):return 'I' if It == 0 else 'X'
   @staticmethod
   def I(It):return 'H' if It == 0 else 'T'
   @staticmethod
   def H(It):return 'U' if It == 0 else 'I'
   @staticmethod
   def U(It):return 'C' if It == 0 else 'H'
   @staticmethod
   def C(It):return 'G' if It == 0 else 'U'
   @staticmethod
   def G(It):return 'J' if It == 0 else 'C'
   @staticmethod
   def J(It):return 'O' if It == 0 else 'G'
   @staticmethod
   def O(It):return 'E' if It == 0 else 'J'
   @staticmethod
   def E(It):return 'B' if It == 0 else 'O'
   @staticmethod
   def B(It):return 'L' if It == 0 else 'E'
   @staticmethod
   def L(It):return 'R' if It == 0 else 'B'
   @staticmethod
   def R(It):return 'P' if It == 0 else 'L'
   @staticmethod
   def P(It):return 'Q' if It == 0 else 'R'
   @staticmethod
   def Q(It):return 'D' if It == 0 else 'P'
   @staticmethod
   def D(It):return 'S' if It == 0 else 'Q'
   @staticmethod
   def S(It):return 'Y' if It == 0 else 'D'
   @staticmethod
   def Y(It):return 'W' if It == 0 else 'S'
   @staticmethod
   def W(It):return 'M' if It == 0 else 'Y'
   @staticmethod
   def M(It):return 'N' if It == 0 else 'W'
   @staticmethod
   def N(It):return 'Z' if It == 0 else 'M'
   @staticmethod
   def Z(It):return 'K' if It == 0 else 'N'
   @staticmethod
   def K(It):return 'F' if It == 0 else 'Z'
   @staticmethod
   def F(It):return 'A' if It == 0 else 'K'
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
   @staticmethod

   #Bit Check
   def Bit(e):
      try:
         int(e)
         return True
      except:
         return False
