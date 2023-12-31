require(gplots) # for textplot function
require(DBI)
require(RMySQL)

makeSVG = FALSE
for(e in commandArgs()) {
  if(e == "--svg") makeSVG = TRUE
}
if(!makeSVG) {
  pdf(file = "lastfm_graphs.pdf", width = 11, height = 8.5, family = "Helvetica",
      title = "LastFM Data", bg = "white")
} else {
  # When running without a X server, this code will segfault
  library(cairoDevice)
}

drv <- dbDriver("MySQL")
con <- dbConnect(drv, dbname = "tfidf")

statement = paste("select count(user_id) as count",
                  " from listen group by artist_id order by count desc")
rs <- dbSendQuery(con, statement)
data <- fetch(rs, n = -1)   # extract all rows

if(makeSVG) {
  Cairo_svg("listeners_per_artist_raw.svg", width = "11.5", height = "9")
}

plot(data$count, type = "l", xlab = "Artists", ylab = "Listeners")
title("Number of Listeners per Artist")


if(makeSVG) {
  Cairo_svg("log_listeners_per_artist_raw.svg", width = "11.5", height = "9")
}

plot(data$count, type = "l", log = "y", xlab = "Artists", ylab = "Listeners")
title("Log Number of Listeners per Artist")

if(makeSVG) {
  Cairo_svg("listeners_per_artist_boxplot.svg", width = "11.5", height = "9")
}

boxplot(data$count, horizontal = TRUE)
title("Listeners per Artist")

#boxplot(data$count[data$count < 50], horizontal = TRUE)
#title("Listeners per Artist < 50")

if(makeSVG) {
  Cairo_svg("listeners_artist_stats.svg", width = "11.5", height = "9")
}

output = capture.output(summary(data))
output = c(output, capture.output(sd(data)))
textplot(output)
title("Listeners per Artist")

if(makeSVG) {
  Cairo_svg("listeners_per_artist.svg", width = "11.5", height = "9")
}

scale.base = 4
scale.max = max(data$count)
log.scale = scale.base ^ seq(from = 0, to = ceiling(log(scale.max, scale.base)), length.out = 10)
h <- hist(data$count, breaks = log.scale, plot = FALSE)
log.labels = c(NA)
for(i in (1:length(log.scale) - 1)) {
  log.labels[i] = paste(floor(log.scale[i]), " - ", floor(log.scale[i + 1]))
}
barplot(h$count, ylab = "Number of Artists", names = log.labels)
title("Number of Listeners per Artist")

if(makeSVG) {
  Cairo_svg("listens_per_artist_raw.svg", width = "11.5", height = "9")
}

statement = paste("select listen_count as sum from artist_total order by sum desc")
rs <- dbSendQuery(con, statement)
data <- fetch(rs, n = -1)
plot(data$sum, type = "l", xlab = "Artists", ylab = "Listens")
title("Number of Listens per Artist")

output = capture.output(summary(data))
output = c(output, capture.output(sd(data)))
textplot(output)

layout(matrix(c(1:2),2,1))

if(makeSVG) {
  Cairo_svg("log_listens_per_artist_raw.svg", width = "11.5", height = "9")
}

plot(data$sum, type = "l", log = "y", xlab = "Artists", ylab = "Listens")
title("Log Number of Listens per Artist")

if(makeSVG) {
  Cairo_svg("vector_length_per_artist_raw.svg", width = "11.5", height = "9")
}

statement = paste("select length from tfidf_length order by length desc")
rs <- dbSendQuery(con, statement)
data <- fetch(rs, n = -1)
plot(data$length, type = "l", xlab = "Artists", ylab = "TF-IDF Vector Length")
title("TF-IDF Vector Length per Artist")

# select user_count, count(user_count) / total_count * 100 as percent from
# (select count(user_id) as user_count from listen group by artist_id) as t1,
# (select count(distinct artist_id) as total_count from listen) as t2
# group by user_count

if(makeSVG) {
  Cairo_svg("listens_per_listener_raw.svg", width = "11.5", height = "9")
}

statement = paste("select user_count, avg(count) as average",
                  " from (select count(user_id) as user_count, avg(count) as count",
                  " from listen group by artist_id) as t1 group by user_count")
rs <- dbSendQuery(con, statement)
data <- fetch(rs, n = -1)
plot(data, type = "l", xlab = "Listeners", ylab = "Average Listens")
lm <- lm(average ~ user_count, data = data)
abline(lm)
title("Average Listens per Listener")

if(makeSVG) {
  Cairo_svg("low_pop_listens_per_listener_raw.svg", width = "11.5", height = "9")
}

statement = paste("select user_count, avg(count) as average",
                  " from (select count(user_id) as user_count, avg(count) as count",
                  " from listen group by artist_id) as t1",
                  " where user_count <= 10 group by user_count")
rs <- dbSendQuery(con, statement)
data <- fetch(rs, n = -1)
plot(data, type = "l", xlab = "Listeners", ylab = "Average Listens")
lm <- lm(average ~ user_count, data = data)
abline(lm)
title("Low Popularity Artist Listens per Listener")

if(makeSVG) {
  Cairo_svg("unique_artists_per_listener_raw.svg", width = "11.5", height = "9")
}

statement = paste("select count(user_id) as user_count",
                  " from (select count(user_id) as user_count, user_id",
                  " from listen group by artist_id having user_count = 1) as t1",
                  " group by user_id order by user_count desc")
rs <- dbSendQuery(con, statement)
data <- fetch(rs, n = -1)
plot(data$user_count, type = "l", xlab = "Listeners", ylab = "Unique Artists", xlim = c(0, 12000))
title("Unique Artists per Listener")

layout(matrix(1,1,1))

if(makeSVG) {
  Cairo_svg("raw_to_tfidf_similarity.svg", width = "11.5", height = "9")
}

#statement = paste("select diff from raw_tfidf_diff order by diff")
#rs <- dbSendQuery(con, statement)
#data <- fetch(rs, n = -1)
#hist(data$diff)
#plot(data$diff, type = "l", xlab = "|TF-IDF - RAW|", ylab = "Count")
#title("Raw v. TF-IDF Cosing Difference")

# textplot(capture.output(summary(lm)))

dbDisconnect(con)
