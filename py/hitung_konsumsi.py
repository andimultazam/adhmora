#!/usr/bin/python
# -*- coding: utf-8 -*-

import MySQLdb as mdb
import time
import datetime

def HitungEnergi(w,t):
    """this is for per minnute calculation"""
    """input:Daya (W); t (hour)"""
    """output:kwh"""

    return (w*t)/(1000*60)

def HitungDayaAktif(v,i,pf):
    """Power for each phase Pp = Vp*Ip*pf"""
    """x = Vp = V_LN; y = Ip = I_1"""
    """output:watt"""
    
    return v*i*pf

def HitungDayaReaktif(v,i,pf):
    """Power for each phase Qp = Vp*Ip*sin tetha"""
    """x = Vp = V_LN; y = Ip = I_1"""
    """output:var"""

    return v*i*(sqrt(1-pf^2));

def AbodemenListrik(r1,rp):
    """y = Rp/kVA_R1 = Rp. 30.200,-"""
    """x = R1 = 2200 VA (misal)"""
    """output:rp"""

    return r1*rp/1000

def HitungTagihan(w,h1,h2,h3,p,a,b):
    """h1 = 20KWh pertama = Rp. 395"""
    """h2 = 20KWh kedua = Rp. 445"""
    """h3 = Per KWh berikutnya = Rp. 495"""
    """w = DayaAktif; p = pajak dalam persen bulat"""
    """a=beban daya,b=abodemen"""

    if w < 20:
        return ((100+p)/100)*(w*h1 + AbodemenListrik(a,b))
    elif 20 < w < 60:
        return ((100+p)/100)*(w*h1 + w*h2 + AbodemenListrik(a,b))
    else:
        return ((100+p)/100)*(w*h1 + w*h2 + w*h3 + AbodemenListrik(a,b))
        
def HitungIKE(x,y):
    """Indeks standar diisi manual oleh admin"""
    """x = Energi (KWh); y = luas lantai (m^2)"""

    return x / y

con = mdb.connect('localhost', 'babeh', 'aing', 'adhmora');

with con:
	#sementara get id utilitas
	idgedung = 2
	gedung_luas = 150
	gedung_fungsi = 3
	gedung_idtarif = 2
	h1 = 395
	h2 = 445
	h3 = 495
	pajak = 3
	beban_daya = 900
	abodemen = 20000
	gedung_tawal = datetime.datetime(2945,8,17)
	gedung_takhir = datetime.datetime(1945,8,17)
	gedung_totalw = 0
	gedung_totalh = 0
	adahitung = False;
	#get lantai
	cur = con.cursor(mdb.cursors.DictCursor)
	query = "select * from lantai l inner join fungsi f on l.fungsi=f.idfungsi where idgedung = "+idgedung.__str__()
	cur.execute(query)
	lantais = cur.fetchall()
	for lantai in lantais:
		#get ruang
		idlantai = lantai['idlantai']
		lantai_fungsi = lantai['fungsi']
		lantai_luas = lantai['luas']
		lantai_tawal = datetime.datetime(2945,8,17)
		lantai_takhir = datetime.datetime(1945,8,17)
		lantai_totalw = 0
		lantai_totalh = 0
		query = "select * from ruang where idlantai = "+idlantai.__str__()
		cur.execute(query)
		ruangs = cur.fetchall()
		for ruang in ruangs:
			#get utilitas
			idruang = ruang['idruang']
			ruang_luas = ruang['luas']
			ruang_tawal = datetime.datetime(2945,8,17)
			ruang_takhir = datetime.datetime(1945,8,17)
			ruang_totalw = 0
			ruang_totalh = 0
			query = "select * from utilitas_pasang where util_tipe = 1 and idruang = "+idruang.__str__()
			cur.execute(query)
			utils = cur.fetchall()
			for util in utils:
				idutil = util['idutilitas_pasang']
				time_now = time.strftime('%Y-%m-%d %H:%M:%S')
				#hitung konsumsi alat
				query = "select * from pengukuran_alat p inner join utilitas_pasang u on u.idutilitas_pasang = p.id_utilitas_pasang where u.last_compute < p.timestamp and u.idutilitas_pasang = "+idutil.__str__()
				cur.execute(query)
				ukurs = cur.fetchall()
				#print(len(ukurs))
				if len(ukurs) > 1 :
					i = 0
					t1 = ""
					w=0
					for ukur in ukurs:
						utilpasang = ukur['idutilitas_pasang']
						t1 = ukur["timestamp"] if not t1 else t1
						if i > 0:
							tx = (ukur["timestamp"]-t2).total_seconds()/60
							V = ukur['VLN']
							A = ukur['A1']
							PF = ukur['PF1']
							p = HitungDayaAktif(V,A,PF)
							#print 'w='+w.__str__()
							w += HitungEnergi(p,tx)
							#print 'p='+p.__str__()
						t2 = ukur["timestamp"]
						i = 1
					ruang_tawal = t1 if ruang_tawal > t1 else ruang_tawal
					ruang_takhir = t2 if ruang_takhir < t2 else ruang_takhir
					#print w
					ruang_totalw += w
					harga = HitungTagihan(w,h1,h2,h3,pajak,beban_daya,abodemen)
					ruang_totalh += harga
					#print harga
					#simpen konsumsi_alat
					query = "insert into konsumsi_alat (id_utilitaspasang,T1,T2,W,harga) values ("+utilpasang.__str__()+",'"+t1.__str__()+"','"+t2.__str__()+"',"+w.__str__()+","+harga.__str__()+")"
					cur.execute(query)
					#set last compute
					query = "update utilitas_pasang set last_compute = '"+t2.__str__()+"' where idutilitas_pasang = "+idutil.__str__()
					cur.execute(query)
					adahitung = True;
				#endif ukurs
			#endfor utils
			#simpen konsumsi_ruang
			if (adahitung):
				query = "insert into konsumsi_ruang (idruang,T1,T2,totalW,total_harga) values ("+idruang.__str__()+",'"+ruang_tawal.__str__()+"','"+ruang_takhir.__str__()+"',"+ruang_totalw.__str__()+","+ruang_totalh.__str__()+")"
				cur.execute(query)
		#endfor ruangs
		if (adahitung):
			lantai_tawal = ruang_tawal if lantai_tawal > ruang_tawal else lantai_tawal
			lantai_takhir = ruang_takhir if lantai_takhir < ruang_takhir else lantai_takhir
			lantai_totalw += ruang_totalw
			lantai_totalh += ruang_totalh
			query = "insert into konsumsi_lantai (idlantai,T1,T2,totalW,total_harga) values ("+idlantai.__str__()+",'"+lantai_tawal.__str__()+"','"+lantai_takhir.__str__()+"',"+lantai_totalw.__str__()+","+lantai_totalh.__str__()+")"
			cur.execute(query)
	#endfor lantais
	if (adahitung):
		gedung_tawal = ruang_tawal if gedung_tawal > ruang_tawal else gedung_tawal
		gedung_takhir = ruang_takhir if gedung_takhir < ruang_takhir else gedung_takhir
		gedung_totalw += lantai_totalw
		gedung_totalh += lantai_totalh
		query = "insert into konsumsi_gedung (idgedung,T1,T2,totalW,total_harga) values ("+idgedung.__str__()+",'"+gedung_tawal.__str__()+"','"+gedung_takhir.__str__()+"',"+gedung_totalw.__str__()+","+gedung_totalh.__str__()+")"
		cur.execute(query)
#end with