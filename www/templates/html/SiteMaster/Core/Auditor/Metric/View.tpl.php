<table data-sortlist="[[0,0],[2,0]]">
    <caption>Marks</caption>
    <thead>
        <tr>
            <th>Mark Name</th>
            <th>Count</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($context->getMetric()->getAllMarks() as $markUsage): ?>
            <?php $mark = $markUsage->getMark(); ?>
            <tr>
                <td>
                    <?php echo $mark->name; ?>
                </td>
                <td>
                    <?php echo $markUsage->getCount(); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
