(setf (get 'ettenmoors 'adjdst) '(rivendell))	
(setf (get 'rivendell 'adjdst) '(weathertop moria ettenmoors lonely_mountain))	

(setf (get 'weathertop 'adjdst) '(bree moria rivendell))	
(setf (get 'bree 'adjdst) '(isengard moria hobbiton buckland weathertop))

(setf (get 'hobbiton 'adjdst) '(bree buckland))
(setf (get 'buckland 'adjdst) '(hobbiton bree))

(setf (get 'isengard 'adjdst) '(river_isen helms_deep moria bree))
(setf (get 'river_isen 'adjdst) '(helms_deep isengard))

(setf (get 'helms_deep 'adjdst) '(river_isen  edoras  moria  isengard))
(setf (get 'edoras 'adjdst) '(argonath minas_tirith lothlorien helms_deep ))

(setf (get 'argonath 'adjdst) '(minas_tirith edoras lothlorien ))
(setf (get 'minas_tirith 'adjdst) '(argonath minas_morgul morannon edoras ))

(setf (get 'minas_morgul 'adjdst) '(mt_doom minas_tirith ))
(setf (get 'mt_doom 'adjdst) '(minas_morgul))

(setf (get 'morannon 'adjdst) '(minas_tirith dol_guldur ))
(setf (get 'dol_guldur 'adjdst) '(morannon mirkwood lothlorien ))

(setf (get 'mirkwood 'adjdst) '(lothlorien dol_guldur lonely_mountain))
(setf (get 'lothlorien 'adjdst) '(edoras dol_guldur mirkwood lonely_mountain argonath moria ))

(setf (get 'lonely_mountain 'adjdst) '(lothlorien mirkwood rivendell))
(setf (get 'moria 'adjdst) '(bree isengard helms_deep lothlorien rivendell weathertop ))
