#!/usr/bin/env ruby

require 'pry'

board = []

class TicTacToeBoard
	def initialize
		@board = Array.new(9, :-)
		#(0..@board.size - 1).each do |row|
		#	@board[row] = Array.new(3, :-)
		#end
	end

	def placeToken(col, row, token)
		@board[(row - 1) * 3 + (col - 1)] = token
	end

	def display
		@board.each_slice(3) do |row|
			puts row.join('|')
		end
	end

	def full?
		!@board.flatten.any?{ |cell| cell == :- }
	end

	def aiMove
		@board[@board.find_index{ |cell| cell == :- }] = :O
	end
end

b = TicTacToeBoard.new

done = false

until done
	move = Readline.readline('Move: [COL ROW]: ', true)
	col, row = move.split.map(&:to_i)
	b.placeToken(col, row, :X)
	b.aiMove unless b.full?
	b.display

	done = b.full?
end