class XOR:
  def __init__(self, State = '0'):
    self.State = State
    self.Capable()
  def Capable(self):
    self.State = self.State + '01'
  def Next(self, n=1):
    for i in range(n):
      self.State = self.State + '001'
      self.Executable()
  def Executable(self):
    self.States = {}
    for i in range(len(self.State)):
      length = len(self.State) - 1
      self.State = self.State[0:length-i] + self.State[(length + 1)-i:] + self.State[length - i: length - i + 1]
      self.States[i] = self.State
    self.State = self.State.replace('100','001')
