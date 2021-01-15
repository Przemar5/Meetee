class DateAdapter {
	constructor (date) {
		if (date instanceof Date) {
			this.dateObj = date
		} else if (typeof date === 'string') {
			this.dateObj = new Date()
			this.dateObj.setHours(0)
			this.dateObj.setMinutes(0)
			this.dateObj.setSeconds(0)
			this.dateObj.setMilliseconds(0)
			this.set(date)
		}
	}

	set (date) {
		if (!date) return
		else if (!this.hasValidFormat(date)) {
			throw Error('Invalid date format.')
		}
		[year, month, date] = date.split('-')
		this.dateObj.setFullYear(year)
		this.dateObj.setMonth(month)
		this.dateObj.setDate(date)
	}

	static getStringFromInterval (interval) {
		let matches = /^(\+|\-)(?: )?(\d+(?: )?years)?(?: )*(\d+(?: )?months)?(?: )*(\d+(?: )?days)?$/
			.exec(interval)
		if (!matches) throw new Error('Bad interval format (+/- n years m months o days).')

		matches = matches.slice(1)
		let date = new DateAdapter(new Date())
		let plus = matches.shift()

		for (let i in matches) {
			if (/years$/.exec(matches[i])) {
				let value = (plus === '+') ? parseInt(matches[i]) : -parseInt(matches[i])
				date.addYears(value)
			}
			else if (/months$/.exec(matches[i])) {
				let value = (plus === '+') ? parseInt(matches[i]) : -parseInt(matches[i])
				date.addMonths(value)
			}
			else if (/days$/.exec(matches[i])) {
				let value = (plus === '+') ? parseInt(matches[i]) : -parseInt(matches[i])
				date.addDays(value)
			}
		}
		return date.get()
	}

	hasValidFormat (date) {
		return /^\d{4}-\d{2}-\d{2}$/.exec(date)
	}

	get () {
		return this.getFullYear() + '-' + 
			this.getMonth() + '-' + this.dateObj.getDate()
	}

	addYears (years) {
		if (!typeof years === 'number') {
			throw new Error('Years count must be a number.')
		}
		this.dateObj.setFullYear(this.dateObj.getFullYear() + years)
	}

	addMonts (months) {
		if (!typeof months === 'number') {
			throw new Error('Months count must be a number.')
		}
		this.dateObj.setMonth(this.dateObj.getMonth() + months)
	}

	addDate (days) {
		if (!typeof days === 'number') {
			throw new Error('Days count must be a number.')
		}
		this.dateObj.setDate(this.dateObj.getDate() + days)
	}

	getFullYear () {
		return this.dateObj.getFullYear()
	}

	getMonth () {
		let month = this.dateObj.getMonth()
		month++
		month = month.toString()

		return (month.length === 1) 
			? '0' + month
			: month
	}

	getDate () {
		let day = this.dateObj.getDate()
		day = day.toString()

		return (day.length === 1) 
			? '0' + day
			: day
	}
}