# Program able to calculate :
# 1. Real TIme Energy Consumption
# 2. Daily/Monthly/Annualy Cost
# 3. IKE (Indeks Konsumsi Energi)

#define function
def HitungEnergi(w,t):
    """this is for per minnute calculation"""
    """Daya (W); t (hour)"""

    return (w*t)/(1000*60)

def HitungDayaAktif(v,i,pf):
    """Power for each phase Pp = Vp*Ip*pf"""
    """x = Vp = V_LN; y = Ip = I_1"""
    
    return v*i*pf

def HitungDayaReaktif(v,i,pf):
    """Power for each phase Qp = Vp*Ip*sin tetha"""
    """x = Vp = V_LN; y = Ip = I_1"""

    return v*i*(sqrt(1-pf^2));

def AbodemenListrik(r1,rp):
    """y = Rp/kVA_R1 = Rp. 30.200,-"""
    """x = R1 = 2200 VA (misal)"""

    return r1*rp/1000
    
def HitungTagihan(w,x,y,z,p):
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
