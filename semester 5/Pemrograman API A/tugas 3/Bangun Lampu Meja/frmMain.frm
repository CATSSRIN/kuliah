VERSION 5.00
Begin VB.Form frmMain 
   BorderStyle     =   0  'None
   Caption         =   "Form1"
   ClientHeight    =   7200
   ClientLeft      =   0
   ClientTop       =   0
   ClientWidth     =   9600
   KeyPreview      =   -1  'True
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   480
   ScaleMode       =   3  'Pixel
   ScaleWidth      =   640
   ShowInTaskbar   =   0   'False
   StartUpPosition =   3  'Windows Default
End
Attribute VB_Name = "frmMain"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Public m_Texture As cTexture        ' Our own texture
Private qObj As Long                ' Handle untuk objek Quadric (UNTUK SILINDER/KERUCUT)


Public Function InitGL() As Boolean
    Dim aflLightAmbient(0 To 3) As GLfloat ' Gunakan 0-based array
    Dim aflLightDiffuse(0 To 3) As GLfloat
    Dim aflLightPosition(0 To 3) As GLfloat

    ' Buat tekstur baru (asumsi cTexture dan file TGA sudah benar)
    Set m_Texture = New cTexture
    If Dir(App.Path & "\Data\Crate.tga") <> "" Then
         m_Texture.loadTexture App.Path & "\Data\Crate.tga", FILETYPE_TGA
    Else
         MsgBox "File Data\Crate.tga tidak ditemukan!", vbCritical
         InitGL = False
         Exit Function
    End If

    ' --- Inisialisasi GLU Quadric Object ---
    qObj = gluNewQuadric()
    If qObj = 0 Then
        MsgBox "Gagal membuat GLU Quadric Object!", vbCritical
        InitGL = False
        Exit Function
    End If
    gluQuadricNormals qObj, GLU_SMOOTH ' Buat normal agar pencahayaan halus
    gluQuadricTexture qObj, GL_TRUE  ' Aktifkan koordinat tekstur otomatis

    ' Aktifkan pemetaan tekstur 2D
    glEnable glcTexture2D
    ' Shading halus
    glShadeModel smSmooth

    ' Set warna & kedalaman clear buffer
    glClearColor 0.1, 0.1, 0.1, 0# ' Sedikit abu-abu gelap
    glClearDepth 1#

    ' Aktifkan Z-buffer (Depth Test)
    glEnable glcDepthTest
    ' Tipe test kedalaman
    glDepthFunc cfLEqual
    ' Koreksi perspektif terbaik
    glHint htPerspectiveCorrectionHint, hmNicest

    ' Pengaturan Cahaya (Seperti sebelumnya)
    aflLightAmbient(0) = 0.4: aflLightAmbient(1) = 0.4: aflLightAmbient(2) = 0.4: aflLightAmbient(3) = 1# ' Sedikit redup
    aflLightDiffuse(0) = 0.8: aflLightDiffuse(1) = 0.8: aflLightDiffuse(2) = 0.8: aflLightDiffuse(3) = 1# ' Tidak terlalu terang
    aflLightPosition(0) = 2#: aflLightPosition(1) = 3#: aflLightPosition(2) = 4#: aflLightPosition(3) = 1# ' Ubah posisi cahaya

    glLightfv ltLight1, lpmAmbient, aflLightAmbient(0)
    glLightfv ltLight1, lpmDiffuse, aflLightDiffuse(0)
    glLightfv ltLight1, lpmPosition, aflLightPosition(0)

    ' Aktifkan cahaya (jika diinginkan, sesuaikan dengan tombol L nanti)
    glEnable glcLighting
    glEnable glcLight1
    ' Aktifkan pewarnaan material berdasarkan glColor
    glEnable glcColorMaterial
    glColorMaterial faceFrontAndBack, cmmAmbientAndDiffuse ' Pengaruhi ambient & diffuse

    InitGL = True
End Function

Private Sub Form_KeyDown(KeyCode As Integer, Shift As Integer)
    ' Set the key to be pressed
    gbKeys(KeyCode) = True
End Sub

Private Sub Form_KeyUp(KeyCode As Integer, Shift As Integer)
    ' Set the key to be not pressed
    gbKeys(KeyCode) = False
End Sub

Private Sub Form_Load()
    Dim bFullscreen As Boolean
    Dim frm As frmMain
    Dim bLightSwitched As Boolean
    Dim bFilterSwitched As Boolean
    Dim bLightOn As Boolean
    Dim giCurrFilter As Integer

    ' Put us into fullscreen automatically
    bFullscreen = True
    bLightSwitched = False
    bFilterSwitched = False
    bLightOn = False
    gflZ = -5#

    ' Save the current display settings
    SaveDisplaySettings

    ' Show this form
    Me.Show
    
    ' Attempt to create a compatible GL window and set the display mode
    If (CreateGLWindow(Me, 1920, 1080, 32, bFullscreen) = False) Then
        Unload Me
    End If
    ' Attempt to set up OpenGL
    If (InitGL() = False) Then
        Unload Me
    End If
  
    ' Loop until the form is unloaded, process windows events every time we're not rendering
    Do While DoEvents()
        ' Render the scene, if it failed or the user has pressed the escape key then exit the program
        If (DrawGLScene() = False) Or (gbKeys(vbKeyEscape)) Then
            Unload Me
        Else
            ' Swap the front and back buffers to display what we've just rendered
            SwapBuffers Me.hDC
      
            ' Toggle lighting
            If (gbKeys(vbKeyL) = True) And (bLightSwitched = False) Then
                bLightOn = Not (bLightOn)
            
                If (bLightOn) Then
                    glEnable glcLighting
                Else
                    glDisable glcLighting
                End If
              
                bLightSwitched = True
            End If
      
            If (gbKeys(vbKeyL) = False) Then
                bLightSwitched = False
            End If
      
            ' Toggle filtering
            If (gbKeys(vbKeyF) = True) And (bFilterSwitched = False) Then
                giCurrFilter = m_Texture.getFilter
                giCurrFilter = giCurrFilter + 1
                If giCurrFilter > 2 Then giCurrFilter = 0
                Select Case giCurrFilter
                    Case 0:
                        m_Texture.setFilter FILTER_NEAREST
                    Case 1:
                        m_Texture.setFilter FILTER_LINEAR
                    Case 2:
                        m_Texture.setFilter FILTER_MIPMAPPED
                End Select
            
                bFilterSwitched = True
            End If
      
            If (gbKeys(vbKeyF) = False) Then
                bFilterSwitched = False
            End If
        
            ' Zoom in and out
            If (gbKeys(vbKeyPageUp) = True) Then
                gflZ = gflZ - 0.1
            End If
            
            If (gbKeys(vbKeyPageDown) = True) Then
                gflZ = gflZ + 0.1
            End If
            
            ' Increase / decrease cube's rotation amount
            If (gbKeys(vbKeyUp) = True) Then
                gflXSpeed = gflXSpeed - 0.01
            End If
            
            If (gbKeys(vbKeyDown) = True) Then
                gflXSpeed = gflXSpeed + 0.01
            End If
            
            If (gbKeys(vbKeyLeft) = True) Then
                gflYSpeed = gflYSpeed - 0.01
            End If
            
            If (gbKeys(vbKeyRight) = True) Then
                gflYSpeed = gflYSpeed + 0.01
            End If
            
            ' Key escape has been pressed, so exit the program!
            If (gbKeys(vbKeyEscape) = True) Then
                Unload Me
            End If
        End If
    Loop
End Sub

Private Sub Form_Resize()
    ' When the user resizes the form, tell OpenGL to update so that it renders to the right place!
    ' Primarily used when in windowed mode
    ReSizeGLScene ScaleWidth, ScaleHeight
End Sub

Public Function DrawGLScene() As Boolean
    Static xrot As GLfloat
    Static yrot As GLfloat

    glClear clrColorBufferBit Or clrDepthBufferBit
    glLoadIdentity

    glTranslatef 0, 0, gflZ
    glRotatef xrot, 1, 0, 0
    glRotatef yrot, 0, 1, 0

    glDisable glcTexture2D

    ' ------ BADAN GHOST: Sphere Lonjong ------
    glColor3f 1, 1, 1
    glPushMatrix
        glScalef 1, 1.2, 1
        gluSphere qObj, 0.75, 32, 32
    glPopMatrix

    ' ------ TANGAN Kiri ------
    glColor3f 1, 1, 1
    glPushMatrix
        glTranslatef -0.65, -0.2, 0.15
        glScalef 1, 0.8, 1
        gluSphere qObj, 0.18, 16, 16
    glPopMatrix
    ' ------ TANGAN Kanan ------
    glColor3f 1, 1, 1
    glPushMatrix
        glTranslatef 0.65, -0.2, 0.15
        glScalef 1, 0.8, 1
        gluSphere qObj, 0.18, 16, 16
    glPopMatrix

    ' ------ Mata kiri (X negatif, Y sama) ------
    glColor3f 0, 0, 0
    glPushMatrix
        glTranslatef -0.22, 0.33, 0.68
        glScalef 1.25, 1, 1
        gluSphere qObj, 0.08, 12, 12
    glPopMatrix

    ' ------ Mata kanan (X positif, Y sama) ------
    glPushMatrix
        glTranslatef 0.22, 0.33, 0.68
        glScalef 1.1, 1, 1
        gluSphere qObj, 0.08, 12, 12
    glPopMatrix

    ' ------ MULUT SEGITIGA HITAM ------
    glColor3f 0, 0, 0
    glPushMatrix
        glTranslatef 0, 0.19, 0.8
        glScalef 1, 0.75, 1#
        glBegin &H4  ' GL_TRIANGLES
            glVertex3f 0, 0.06, 0
            glVertex3f -0.025, -0.02, 0
            glVertex3f 0.025, -0.02, 0
        glEnd
    glPopMatrix

    xrot = xrot + gflXSpeed
    yrot = yrot + gflYSpeed

    DrawGLScene = True
End Function




Private Sub Form_Unload(Cancel As Integer)
    ' Hapus GLU Quadric Object jika sudah dibuat
    If qObj <> 0 Then
        gluDeleteQuadric qObj
        qObj = 0 ' Set ke 0 agar aman
    End If

    ' Shut down OpenGL (Kode lama)
    KillGLWindow Me
End Sub
