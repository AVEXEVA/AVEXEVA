class eXt(X as object):
   def __init__(self, e):
      self.e = e
   def __str__(self):
      return ''.format(self.val)

def Letteret(text, It = 0):
   return Lettere(text.strip().upper().replace('T', 't'), It)

def Lettere(tex, It):
   text = {}
   t = []
   te = tex[0]
   while len(tex) != 0 and tex != 't' and tex != '':
      t = [Letteri(tex, 0), tex[0:1], Letteri(tex, 1)]
      xt = 0
      x = 0
      t = str(t)
      for e in t:
         if xt == len(tex) / 2:
            x = x ^ 1
         t = t[:x - 1] + Letterex(e, x, It) + t[x + 1:]
         xt = xt + 1
      text = {tex[:1] : t}
      tex = tex[1:]
   return text

def Letteri(tex, It):
   return str(Letterto({tex[:1] : tex[1:]}, It, It))

def Letterto(tex, Ex, It):
      t, x = tex.popitem()
      if x == '' or t == '':
         return ''
      if Ex == 0:
         if It == 0:
             return {Letters()[t](not It) : Letteri(x, It)} if not Bit(t) else {Letters()[bin(int(t))[2:3].replace('0','O').replace('1','Z')](not It) : Letteri(bin(int(t))[2:3].replace('0','NE').replace('1','ERO') + bin(int(t))[3:], It)}
            
         else:
             return {Letteri(x, It) : Letters()[t](It ^ 1)} if not Bit(t) else {Letters()[bin(int(t))[2:3].replace('0','O').replace('1','Z')](It ^ 1)}
      else:
         if It == 0:
             return {Letters()[t](It) : Letteri(x, It)} if not Bit(t) else {Letters()[bin(int(t))[2:3].replace('0','O').replace('1','Z')](It) : Letteri(bin(int(t))[2:3].replace('0','NE').replace('1','ERO') + bin(int(t))[3:], It)}
         else:
             return {Letteri(x, It) : Letters()[t](It)} if not Bit(t) else {Letteri(bin(int(t))[2:3].replace('0','NE').replace('1','ERO') + bin(int(t))[3:], It) : Letters()[bin(int(t))[2:3].replace('0','O').replace('1','Z')](It)}
            
def Letterex(e, x, It):
   if e != '\\' and e != '/':
      return e
   elif e == '\\' and x == 0:
      return '\\'
   else:
      return '/'

def Letters():
   return {
      'A' : A,
      'V' : V,
      'X' : X,
      't' : t,
      'I' : I,
      'H' : H,
      'U' : U,
      'C' : C,
      'G' : G,
      'J' : J,
      'O' : O,
      'E' : E,
      'B' : B,
      'L' : L,
      'R' : R,
      'P' : P,
      'Q' : Q,
      'D' : D,
      'S' : S,
      'Y' : Y,
      'W' : W,
      'M' : M,
      'N' : N,
      'Z' : Z,
      'K' : K,
      'F' : F,
   }

def Bit(e):
   try:
      int(e)
      return True
   except:
      return False

def A(It):return 'V' if It == 1 else 'F'
def V(It):return 'X' if It == 1 else 'A'
def X(It):return 't' if It == 1 else 'V'
def t(It):return 'I' if It == 1 else 'X'
def I(It):return 'H' if It == 1 else 't'
def H(It):return 'U' if It == 1 else 'I'
def U(It):return 'C' if It == 1 else 'H'
def C(It):return 'G' if It == 1 else 'U'
def G(It):return 'J' if It == 1 else 'C'
def J(It):return 'O' if It == 1 else 'G'
def O(It):return 'E' if It == 1 else 'J'
def E(It):return 'B' if It == 1 else 'O'
def B(It):return 'L' if It == 1 else 'E'
def L(It):return 'R' if It == 1 else 'B'
def R(It):return 'P' if It == 1 else 'L'
def P(It):return 'Q' if It == 1 else 'R'
def Q(It):return 'D' if It == 1 else 'P'
def D(It):return 'S' if It == 1 else 'Q'
def S(It):return 'Y' if It == 1 else 'D'
def Y(It):return 'W' if It == 1 else 'S'
def W(It):return 'M' if It == 1 else 'Y'
def M(It):return 'N' if It == 1 else 'W'
def N(It):return 'Z' if It == 1 else 'M'
def Z(It):return 'K' if It == 1 else 'N'
def K(It):return 'F' if It == 1 else 'Z'
def F(It):return 'A' if It == 1 else 'K'

while print('Crept=', Letteret(input('String='))):
   continue
