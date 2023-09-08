#!/usr/bin/env ruby

# Simple minesweeper program to prep for Triplebyte interview
#
# Author: dysbulic <dys@dhappy.org>
# Date: 2019/09/11

require 'arg-parser'
require 'readline'
require 'pry'

class Minesweeper
	include ArgParser::DSL

	keyword_arg :width, 'Number of columns'
	keyword_arg :height, 'Number of rows'
	keyword_arg :mines, 'Number of mines'
	flag_arg :cheat, default: false
	flag_arg :debug, default: false

	def run
		cols = nil
		rows = nil
		num_mines = nil

		if opts = parse_arguments
			cols = opts.width.to_i
			rows = opts.height.to_i
			num_mines = opts.mines.to_i
		end

		while !cols.is_a?(Integer) || cols < 1
			cols = Readline.readline('Columns: ').to_i
		end

		while !rows.is_a?(Integer) || rows < 1
			rows = Readline.readline('Rows: ').to_i
		end

		while !num_mines.is_a?(Integer) || num_mines < 1 || num_mines >= cols * rows
			num_mines = Readline.readline('Number of Mines: ').to_i
			if num_mines >= cols * rows
				STDERR.puts 'Error: More mines than squares'
			end
		end

		board = []
		(1..rows).each{ |row| board[row - 1] = Array.new(cols) }

		def board.display(show_mines = false)
			(1..self.size).each do |row|
				print '+'
				(1..self[row - 1].size).each{ print '-+' }
				puts

				print '|'
				(1..self[row - 1].size).each do |col|
					if self[row - 1][col - 1] == :mine && show_mines
						print '*'
					elsif self[row - 1][col - 1].is_a?(Integer)
						print self[row - 1][col - 1]
					else
						print ' '
					end
					print '|'
				end
				puts

				if row == self.size
					print '+'
					(1..self[row - 1].size).each{ print '-+' }
					puts
				end
			end
		end

		# https://stackoverflow.com/a/7664677/264008
		def board.[](idx)
			return super(idx) unless idx < 0
			return nil
		end

		num_squares = cols * rows
		mines = (0..(num_squares - 1)).to_a.sample(num_mines).map do |idx|
			col = idx % cols
			row = idx / cols
			board[row][col] = :mine
		end

		done = false

		until done do
			board.display(opts.cheat)
			input = Readline.readline('Cell to Test: [COL ROW]: ', true)
			col, row = input.split.map(&:to_i)

			if board[row - 1][col - 1] == :mine
				board.display(true)
				puts 'BOOM!'
				done = true unless opts.cheat
			else
				neighbors = [
					[col - 2, row - 2], [col - 2, row - 1], [col - 2, row - 0],
					[col - 1, row - 2], [col - 1, row - 0],
					[col - 0, row - 2], [col - 0, row - 1], [col - 0, row - 0]
				]

				puts "Check: #{neighbors}" if opts.debug

				counts = neighbors.map do |idxs|
					unless idxs.any?{ |idx| idx < 0 }
						1 if board.dig(*idxs.reverse) == :mine
					end
				end

				puts "Found: #{counts}" if opts.debug

				board[row - 1][col - 1] = counts.compact.sum
			end

			if board.flatten.count(nil).zero?
				board.display(true)
				puts 'WINNER!'
				done = true
			end
		end
	end
end

begin
	Minesweeper.new.run
rescue Interrupt
	puts "\nBYE!"
end