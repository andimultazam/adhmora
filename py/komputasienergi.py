# Program able to calculate :
# 1. Real TIme Energy Consumption
# 2. Daily/Monthly/Annualy Cost
# 3. IKE (Indeks Konsumsi Energi)

#define function
def HitungEnergi(x,y):
    """this is for per minnute calculation"""
    """x = Daya (W); y = t (hour)"""

    return (x*y)/(1000*60)

def HitungDayaAktif(x,y,z):
    """Power for each phase Pp = Vp*Ip*pf"""
    """x = Vp = V_LN; y = Ip = I_1"""
    
    return x*y*z

def HitungDayaReaktif(x,y,z):
    """Power for each phase Qp = Vp*Ip*sin tetha"""
    """x = Vp = V_LN; y = Ip = I_1"""

    return x*y*(sqrt(1-z^2));

def AbodemenListrik(x,y):
    """y = Rp/kVA_R1 = Rp. 30.200,-"""
    """x = R1 = 2200 VA (misal)"""

    return x*y/1000
    
def HitungTagihan(w,x,y,z):
    """x = 20KWh pertama = Rp. 395"""
    """y = 20KWh kedua = Rp. 445"""
    """z = Per KWh berikutnya = Rp. 495"""
    """w = DayaAktif; p = pajak"""

    if w < 20:
        return ((100+p)/100)*(w*x + w*x + AbodemenListrik(a,b))
    elif 20 < w < 60:
        return ((100+p)/100)*(w*x + w*y + AbodemenListrik(a,b))
    else
        return ((100+p)/100)*(w*x + w*y + w*z + AbodemenListrik(a,b))

def HitungIKE(x,y):
    """Indeks standar diisi manual oleh admin"""
    """x = Energi (KWh); y = luas lantai (m^2)"""

    return x / y

# Take input from database
