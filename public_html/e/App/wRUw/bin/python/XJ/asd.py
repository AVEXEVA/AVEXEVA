def Letteret(text, It = 0):
   return Lettere(text.strip().upper().replace('T', 't'), It)

def Lettere(tex, It):
   text = {}
   t = []
   while len(tex) != 0 and tex != 't' and tex != '':
      if tex == '':
         continue
      t = [Letteri(tex, 0), Letteri(tex, 1)] if len(tex) % 2 == 1 else [Letteri(tex, 0), tex[0:1], Letteri(tex, 1)]
      text = {tex[:1] : t}
      tex = tex[1:] #if isinstance(tex, str) else str(tex[1:])
   return t

def Letteri(tex, It):
   return Lettero({tex[:1] : tex[1:]}, It)

def Lettero(tex, It):
   for t, x in tex.items():
      print(t)
      print(x)
      print(tex.items())
      if It == 1:
          return {''.join(filter(str.isalnum, str(Letteri(x, It) if x != '' else ''))) if len(x) > 0 else '' : {Letters()[t](It) : Letteri(x, It)}} if len(x) == '' else ''
      else:
          return {Letters()[t](It) :  Letteri(x, It)} if len(x) > 0 else ''

def Bit(e):
   try:
      int(e)
      return True
   except:
      return False

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
