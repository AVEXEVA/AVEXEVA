import os
class XER:
	def __init__(self):self.XOR()
	@staticmethod
	def XOR():
		XER = {}
		Rel = "../../../XOR/"
		for folder in os.listdir(Rel):
			if os.path.isdir(Rel + folder):
				for file in os.listdir(Rel + folder):
					if len(file) == 1:
						if file in XER:
							XER[file][folder] = open(Rel + folder + "/" + file, "r")
						else:
							XER[file] = {}
							XER[file][folder] = open(Rel + folder + "/" + file, "r")
		return XER