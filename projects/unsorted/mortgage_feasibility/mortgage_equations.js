var determinations = new Array(); // equations for finding various variables

determinations['payment'] = function(values) {
  var r = values['interest'] / (12 * 100);
  var z = 1 + r;
  var t = values['term'] * 12;
  var loan = (values['cost'] - values['downpayment']);
  var rate = 1;
  if(t != 0 && z != 1) { // will cause a divide by 0
    loan *= Math.pow(z, t);
    rate = -r / (1 - Math.pow(z, t));
  } else if(z == 1 && t != 0) {
    loan = loan / t;
  }
  values['payment'] = loan * rate;
}

determinations['loan'] = function(values) {
  var r = values['interest'] / (12 * 100);
  var z = 1 + r;
  var t = values['term'] * 12;
  var rate = t;
  if(r != 0) { // will cause a divide by 0
    rate = (1 - Math.pow(z, t)) / (-r * Math.pow(z, t))
      }
  values['loan'] = values['payment'] * rate;
}

determinations['cost'] = function(values) {
  determinations['loan'](values);
  values['cost'] = values['loan'] + values['downpayment'];
}

determinations['downpayment'] = function(values) {
  determinations['loan'](values);
  values['downpayment'] = values['cost'] - values['loan'];
}

/*
// From: http://www.hughchou.org/calc/formula.html
// Use a birary search rather than an equation
determinations['interest'] = function(values) {
  var max_rate = 10000000000, min_rate = -max_rate;
  var saved_payment = values['payment'];
  while(min_rate < max_rate - 0.0001) {
    values['interest'] = (min_rate + max_rate) / 2;
    determinations['payment'](values);
    if(values['payment'] < saved_payment) max_rate = values['interest'];
    else min_rate = values['interest'];
  }
  values['payment'] = saved_payment;
}
*/
