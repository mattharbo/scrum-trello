For all event returned by the API call

1. Check the "type" of event
2. For
	updateCard
		get type
		get date
		get memberCreator > fullName (person who performed the action)
		get data > card > name (name of the card updated)
		get data > listAfter > name  (new list in which card has been moved)
		get data > listBefore > name  (new list in which card has been moved)
	moveCardToBoard
		get type
		get date
		get memberCreator > fullName (person who performed the action)
		get data > card > name (name of the card updated)
		get data > boardSource > name (name of the provenance board)
		get data > list > name (name of the list in which it has been moved)
	createCard
		get type
		get date
		get memberCreator > fullName (person who performed the action)
		get data > card > name (name of the card updated)
		gat data > list > name (name of the list in which it has been created)