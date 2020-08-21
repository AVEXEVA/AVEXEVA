Args = Input.split(',')
f = open(Args[0], 'a')
if len(Args)> 1:
  f.write(Args[1])
f.close()