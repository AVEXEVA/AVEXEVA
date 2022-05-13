import os
class Index:
  Path = '/'
  Content = {
    Files   : None,
    Folders : None
  }
  def __init__( self, Path ):
    self.Path = Path
    self.Contents = Index.Scan( Path )
  @staticmethod
  def Scan( Path ):
    Contents = {
      Files   : [],
      Folders : []
    }
    for resource in os.scandir( Path ):
      if resource.is_dir(): Contents[ 'Files'].append( resource.name )
      elif resource.is_file(): Contents[ 'Folders' ].append( resource.name )
    return Contents
      
