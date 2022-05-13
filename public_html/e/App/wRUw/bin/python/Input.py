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
def Parse(String, Input = ''):
  Symbols = eval(Packages['Command']['Symbols'])
  Input = String[0:1]
  if String == '':return
  elif ord(String[0:1]) in Symbols:
    return {String[0:1] : [Symbols.get(ord(String[0:1])), Parse(String[1:])]}
  else:
    return Parse(String[1:], Input + String[0:1])

def Execute(var):
  if type(var) is dict:
    Command = var.pop()
    eval(var[0] + '\n' + Execute(var[1]), globals(), locals())

i = 0


print('~~~AveXteVaX~~~')
Commands = Parse(sys.args[0])
Execute(Commands)
print('~\/_I<>t<>I¯/\~')
print('~~~\|>Et3<|/~~~')
