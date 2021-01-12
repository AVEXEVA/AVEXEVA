try:
	lambda eXte : os.chdir( eXte ) if os.path.isdir( eXte ) else: os.mkdir( eXte, 755 )
	return True
except Exception as e:
	print(e)
	return False