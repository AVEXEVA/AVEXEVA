
class Hand:
    def __init__(self):
        return self.Hand()
    @staticmethod
    def Hand(self, Fingers = True):
        return HandFinger(Fingers)
    @staticmethod
    def HandFinger(self, Fingers = True):
        return {
            'Thumb' : self.ThumbFinger(),
            'Index' : self.IndexFinger(),
            'Middle': self.MiddleFinger(),
            'Ring'  : self.RingFinger(),
            'Pinkie': self.PinkieFinger()
        } if Fingers == True else Fingers
        return
    @staticmethod
    def ThumbFinger(self):
        return 'Thumb Finger'
    @staticmethod
    def IndexFinger(self):
        return 'Index Finger'
    @staticmethod
    def MiddleFinger(self):
        return 'Middle Finger'
    @staticmethod
    def RingFinger(self):
        return 'Ring Finger'
    @staticmethod
    def PinkieFinger(self):
        return 'Pink Finger'
