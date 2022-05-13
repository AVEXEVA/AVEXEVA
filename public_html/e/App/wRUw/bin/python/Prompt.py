import os, sys, re
n = 0
Packages = {}
Paths = []

#Packages
for package in os.listdir('Packages'):
  Packages[ package ] = {}
  sys.path.insert( n, 'Packages/' + package )
  for module in os.listdir( 'Packages/' + package ):
     if module[ len(module) - 3 : ] == '.py' and module[0:2] != '__':
        module = module[  : len(module) - 3]
        try:
           Packages[ package ][ module ] = open("Packages/" + package + "/" + module + ".py").read()
           continue
        except:
           print( 'Failed : ' + package + ' / ' + module )
           continue

#Functions
def Execute(String):
  Symbols = eval(Packages['Command']['Symbols'])
  if ord(String[0:1]) not in Symbols:
    return
  index = 1
  Strings = ''
  while index < len(String[1:]):
    if ord(String[index:index+1]) in Symbols:
      break
    else:
      Strings = Strings + String[index:index+1]
    index = index + 1
  exec(Packages['Command'][Symbols.get(ord(String[0:1]))], {}, {'Input' : Strings})
  if index != len(String[1:]):
    return Execute(String[index:index+1])
  
i = 0

while i == 0:
  Input = raw_input("avext3@")
  if i == 1 or Input == '1':
    break
  else:
    Execute(Input)
