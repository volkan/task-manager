import React, { Component } from 'react';
import axios from 'axios'
import './App.css';
import TodoList from './components/TodoList'
import TodoItems from './components/TodoItems'

const currentItemCons = {
  id: '',
  name: '',
  description: '',
  dueDate: '',
  user: ''  
}

const apiUrl = process.env.REACT_APP_API_URL || ''

class App extends Component {
  inputElement = React.createRef()
  constructor() {
    super()
    console.log("constructor")
    this.state = {
      items: [],
      currentItem: currentItemCons,
    }  
  }
  componentDidMount() {    
    axios.get(apiUrl + '/api/v1/tasks')
      .then(res => {
        const items = res.data;
        this.setState({ items });
        console.log("componentDidMount")
      })
  }
  deleteItem = id => {
    const filteredItems = this.state.items.filter(item => {
      return item.id !== id
    })
    axios.delete(apiUrl + `/api/v1/tasks/${id}`)
      .then(res => this.setState({ 
          items: filteredItems
      }));
  }

  handleInput = e => {
    console.log("handleInput")
    const itemText = e.target.value
    
    const currentItem = {
      name: itemText,
      description: 'desc',
      dueDate: new Date().toLocaleString("en-US"),
      user: 'user1'
    }
    this.setState({
      currentItem,
    })
  }
  addItem = e => {
    console.log("addItem")
    e.preventDefault()
    const newItem = this.state.currentItem
    if (newItem.name !== '') {
      axios.post(apiUrl + '/api/v1/tasks', {
        name: newItem.name,
        description: newItem.description,
        dueDate: newItem.dueDate,
        user: newItem.user
      })
      .then(res => {
        newItem.id = res.data.id
        this.setState({ 
          items: [...this.state.items, newItem]
        });
        console.log("componentDidMount")
      });
    }
  }
  render() {
    return (
      <div className="App">
        <TodoList
          addItem={this.addItem}
          inputElement={this.inputElement}
          handleInput={this.handleInput}
          currentItem={this.state.currentItem}
        />
        <TodoItems entries={this.state.items} deleteItem={this.deleteItem} />
      </div>
    )
  }
}

export default App
