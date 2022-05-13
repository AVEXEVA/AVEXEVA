#Filizer
class Filizer(object):
    def __init__(self, File):
        self.file = File
        self.Bits(self.Charset(self.Characters(self.Words(self.Lines(self.Read(self.Open(self.File)))))))
        return
    @staticmethod
    def Open(file):
        self.open = open(File)
        return self.open
    @staticmethod
    def Read(open):
        self.Read = File.read()
        return self.open
    @staticmethod
    def Lines(read):
        self.Lines = []
        while Line := self.Line(lines):
            self.Lines.append(Line)
        return self.Lines
    @staticmethod
    def Write(self, text)
        self.file.write(text)
        return
