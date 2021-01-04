class Sex(object):
    @staticmethod
    def __init__(self):
        return
    @staticmethod
    def A(self, to):
        return 'A'
    @staticmethod
    def Smile(self, to):
        return 'Smile'
    @staticmethod
    def Twinkle(self, to):
        return 'Twinkle'
    @staticmethod
    def Wink(self, to):
        return 'Wink'
    @staticmethod
    def ButterflyStomach(self, to):
        return 'Butterfly Stomach'
    @staticmethod
    def Hug(self, to):
        return 'Hug'
    @staticmethod
    def Kiss(self, to, tongue = False):
        return self.FrenchKiss() if tongue else 'Kiss'
    @staticmethod
    def FrenchKiss(self, to):
        return 'French Kiss'
    @staticmethod
    def Hands(self, to, hands = []):
        return {
            'Left' : self.Hand(self, to, hands[0]
            'Right': self.Hand(self, to, hands[1]
        }
    
    
    @staticmethod
    def Head(self, to, hands = []):
        return {
            'Tongue' : Body.Tongue(),
            'Hands'  : [
                'Left  Hand' : Body.LeftHand(),
                'Right Hand' : Body.RightHand()
            ]
        }
