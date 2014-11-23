# Program able to calculate :
# 1. Real TIme Energy Consumption
# 2. Daily/Monthly/Annualy Cost
# 3. IKE (Indeks Konsumsi Energi)

#define function
def HitungEnergi(x,y):
    """This function calculate energy consumption"""
    """Energy in KWh"""
    """the function for per minnute calculation"""

    return (x*y)/(1000*60)

def HitungDayaAktif(x,y,z):
    """Power for each phase Pp = Vp*Ip*pf"""
    """Vp = V_LN"""
    """Ip = I_1"""

    return x*y*z

def HitungDayaReaktif(x,y,z):
    """Power for each phase Pp = Vp*Ip*pf"""
    """sin tetha = sqrt(1-cos tetha^2)"""

    return x*y*(sqrt(1-z^2));

def AbodemenListrik(x,y):
    """biaya Rp/kVA R1 = Rp. 30.200,-"""

    return x*y/1000
    
def HitungTagihan(p,w,x,y,z):
    """Harga tagihan tahun 2014 : Rp 575/KWh"""
    """20KWh pertama = Rp. 395"""
    """20KWh kedua = Rp. 445"""
    """Per KWh berikutnya = Rp. 495"""

    if w < 20:
        return ((100+p)/100)*(w*x + 0.03*w*x + AbodemenListrik(a,b))
    elif 20 < w < 60:
        return ((100+p)/100)*(w*x + w*y + AbodemenListrik(a,b))
    else
        return ((100+p)/100)*(w*x + w*y + w*z + AbodemenListrik(a,b))

def HitungIKE(x,y):
    """Indeks standar diisi manual oleh admin"""

    return x / y

# Take input from database
