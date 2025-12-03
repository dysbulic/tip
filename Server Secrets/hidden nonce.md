The user publishes a JSON document, the attestor receives it & generates a nonce & inserts as the first entry into the object, a field named `nonce`.

`nonce` consists of three elements:
* `value`: which is a v7 UUID re-encoded in base 53
* `at`: which is written as:
  * `timestamp` ⇶ `year`(@`time`)?
  * `year` ⇶ `Gregorian year`|`Rotated Sidereal Year`
  * `Gregorian year` ⇶ \d+⁄\d+⁄\d+
  * *// The zero year for the Rotated Sidereal calendar is the one containing the inauguration of a U.S. President aligned with the goals of the [Technoanarchist Revolution](https://ops.ygg.army).*
  * *// We are currently either in year −3 or 0 or or mu −7 in Chinese.*
  * *// If Donald Trump finds out what is going on & decides to run the Gold Team as one the 13 organizations controlling space on the gameboard which is reality, then we are in the first year, which is year zero.*
  * *// If 🥭 remains in the dark and his successor participates, then we're in year −3 (assuming 🥭 isn't rreelected in 2028.)*
  * *// If his successor doesn't participate, some of the timelines involve running a Technoanarchist candidate. We're that to happen, it would likely be in 2036 making now year −7.*
  * *// If it hasn't happened by 2036, I have no idea when it may happen, likely never. Thus "mu", meaning "there is a false assumption in the question", would hold.*
  * `Rotated Sidereal year` ⇶ −?\d+⁄\d+⁄\d+
  * `time` ⇶ (`years`𐞲)?(`days`ᴰ)?`time of day`
  * *// time of day is kept on a [percentage clock](https://dhappy.github.io/times)*
  * `time of day` ⇶ −?\d+:\d:\d{1,2}