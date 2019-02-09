var viewUser = -1;
document.body.addEventListener('click', function (ev) {
    const classList = ev.target.classList;

    if (classList.contains('user-task')) { // get user tasks

        try {
            const attr = ev.target.getAttribute('href');
            // if -1 -> admin tasks || 0 -> all users tasks || >0 -> one user task
            const id = attr.indexOf('admin') !== -1 ? -1 : (attr.indexOf('all') !== -1 ? 0 : attr.split('-')[1]);
            getTask(id);
            const header = document.getElementById('header-count');
            header.querySelector('.name>span').innerText = ev.target.innerText;
        } catch (e) { }
    } else if (classList.contains('add-task')) { // add task
        const user = document.getElementById('user-select');
        const userId = user && !user.hasAttribute('disabled') ? user.value : -1;
        axios.post('task', {
            id: userId,
            name: document.getElementById('task-name').value
        }).then(function (response) {
            document.getElementById('exampleModal').click();
            if (response.data.status === 'OK') {
                if (viewUser === userId || (viewUser === 0 && response.data.data.task.user.id !== response.data.data.task.author.id)) {
                    createTask(response.data.data.task);
                    setCountTasks();
                }
            }
        })
    } else if (classList.contains('check-task')) { // resolve task
        const check = ev.target;
        const id = check.nextElementSibling.value;
        axios.patch('task/' + id, {
            checked: check.checked
        }).then(function (response) {

            if (response.data.status === 'OK') {
                const taskElement = check.closest('.list-group-item');
                const boxDate = taskElement.querySelector('.task-date');

                taskElement.classList.toggle(response.data.data.task.user.id === response.data.data.task.author.id
                    ? 'list-group-item-info' : 'list-group-item-danger', !response.data.data.task.checked);
                taskElement.classList.toggle('list-group-item-success', response.data.data.task.checked);

                check.closest('.list-group-item').querySelector('.title').classList.toggle('resolved', response.data.data.task.checked);

                if (response.data.data.task.checked) {
                    const resolvedElement = document.createElement('div');
                    resolvedElement.classList.add('task-date-resolved');
                    resolvedElement.innerHTML = '<span>Resolved at:</span> <span class="text-success">' + response.data.data.task.resolved_at + '</span>';
                    boxDate.insertBefore(resolvedElement, boxDate.firstChild);
                } else {
                    boxDate.querySelector('.task-date-resolved').remove();
                }
            }
        });
    }
}, false);

const getTask = function (id) {
    viewUser = id;
    const panel = document.getElementById('task-container');

    axios.get('task', {
        params: {
            id: id
        }
    }).then(function (response) {
        panel.querySelector('.tasks').innerHTML = response.data;
        setCountTasks(document.querySelectorAll('.list-group-item') ? document.querySelectorAll('.list-group-item').length : 0);
    });
};

const setCountTasks = function (length) {
    const header = document.getElementById('header-count');
    header.querySelector('small>.count').innerText = length !== undefined ? length : parseInt(header.querySelector('small>.count').innerText) + 1;
};

const createTask = function (task) {
    const newTask = document.createElement('li');
    newTask.classList.add('list-group-item', task.user.id !== task.author.id ? 'list-group-item-danger' : 'list-group-item-info');
    newTask.innerHTML = '<div class="task-date text-muted">' +
        '                    <div class="task-date-created"><span>Created at:</span> <span class="text-info">' + task.created_at + '</span></div>' +
        '                </div>' +
        '                <div class="custom-control">' +
        '                    <input type="checkbox" class="custom-control-input check-task" title="" ' + (task.author.id !== task.user.id ? 'disabled' : '') + '>' +
        '                    <input type="hidden" value="' + task.id + '">' +
        '                </div>' +
        '                <div class="title">' + task.name + '</div>' +
        '                <div class="task-user text-muted">' +
        '                    <div class="task-detail"><span>Created by:</span> <span class="text-warning"><strong>' + task.author.name + '</strong>' + (task.user.id !== task.author.id ? (' for <strong>' + task.user.name + '</strong>') : '') + '</span></div>' +
        '                </div>';

    const appened = function () {
        const group = document.querySelector('.list-group');
        if (group) {
            group.insertBefore(newTask, group.firstChild);
            return true;
        }
        return false;
    };

    if (!appened()) {
        const box = document.querySelector('.tasks');
        box.innerHTML = '<div class="row"><div class="col-md-12"><ul class="list-group"></ul></div></div>';
        appened();
    }
};

(function () {
    getTask(-1);
}());